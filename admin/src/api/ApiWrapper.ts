export interface ApiConfig {
  baseUrl?: string;
  headers?: Record<string, string>;
  timeout?: number;
  prefix?: string;
}

export interface RequestConfig extends ApiConfig {
  params?: Record<string, any>;
  withCredentials?: boolean;
  _isRetry?: boolean;
}

export class ApiError extends Error {
  public status: number;
  public data?: any;

  constructor(message: string, status: number, data?: any) {
    super(message);
    this.name = 'ApiError';
    this.status = status;
    this.data = data;
  }
}

export class ApiWrapper {
  private baseUrl: string;
  private defaultHeaders: Record<string, string>;
  private timeout: number;

  constructor(config: ApiConfig = {}) {
    this.baseUrl = config.baseUrl || 'http://coffee.test/api';
    this.defaultHeaders = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      ...config.headers,
    };
    this.timeout = config.timeout || 10000;
  }

  private buildQueryString(params?: Record<string, any>): string {
    if (!params) return '';
    const query = Object.entries(params)
      .filter(([, v]) => v !== undefined && v !== null)
      .map(([k, v]) => `${encodeURIComponent(k)}=${encodeURIComponent(v)}`)
      .join('&');
    return query ? `?${query}` : '';
  }

  private buildHeaders(configHeaders?: Record<string, string>, isFormData: boolean = false): Record<string, string | undefined> {
    const token = localStorage.getItem('accessToken');
    const headers: Record<string, string | undefined> = {
      'Accept': 'application/json',
      ...(token ? { Authorization: `Bearer ${token}` } : {}),
      ...(!isFormData ? { 'Content-Type': 'application/json' } : {}),
      ...configHeaders,
    };
  
    if (isFormData && 'Content-Type' in headers) {
      delete headers['Content-Type'];
    }
  
    return headers;
  }

  private buildRequestInit(
    method: string,
    headers: Record<string, string | undefined>,
    data: any,
    config: RequestConfig
  ): RequestInit {
    const withBody = data && !['GET', 'HEAD'].includes(method.toUpperCase());
    const isFormData = data instanceof FormData;
  
    const adjustedHeaders: Record<string, string> = {};
    for (const [key, value] of Object.entries(headers)) {
      if (value !== undefined) {
        adjustedHeaders[key] = value;
      }
    }
  
    if (isFormData && 'Content-Type' in adjustedHeaders) {
      delete adjustedHeaders['Content-Type'];
    }
  
    return {
      method,
      headers: adjustedHeaders,
      credentials: config.withCredentials ? 'include' : 'same-origin',
      ...(withBody ? { body: isFormData ? data : JSON.stringify(data) } : {}),
    };
  }


  private async parseResponse<T>(response: Response): Promise<T> {
    const contentType = response.headers.get('content-type') || '';

    if (!response.ok) {
      const errorData = contentType.includes('application/json')
        ? await response.json().catch(() => ({}))
        : {};
      throw new ApiError(
        errorData.message || `HTTP Error: ${response.status}`,
        response.status,
        errorData
      );
    }

    return contentType.includes('application/json')
      ? await response.json()
      : (response as any);
  }

  private async request<T>(
    method: string,
    endpoint: string,
    data?: any,
    config: RequestConfig = {}
  ): Promise<T> {
    const baseUrl = config.baseUrl || this.baseUrl;
    const query = method.toUpperCase() === 'GET' ? this.buildQueryString(config.params) : '';
    const isFormData = data instanceof FormData;
    const headers = this.buildHeaders(config.headers, isFormData);
    const requestInit = this.buildRequestInit(method, headers, data, config);
  
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), this.timeout);
    requestInit.signal = controller.signal;
  
    try {
      const response = await fetch(baseUrl + endpoint + query, requestInit);
      clearTimeout(timeoutId);
      return await this.parseResponse<T>(response);
    } catch (error: any) {
      clearTimeout(timeoutId);
  
      if (error instanceof ApiError) throw error;
      if (error.name === 'AbortError') {
        throw new ApiError('Request timeout', 408);
      }
  
      throw new ApiError(error.message || 'Network error', 0, { originalError: error });
    }
  }

  async get<T>(endpoint: string, config?: RequestConfig): Promise<T> {
    return this.request<T>('GET', endpoint, undefined, config);
  }

  async post<T>(endpoint: string, data?: any, config?: RequestConfig): Promise<T> {
    return this.request<T>('POST', endpoint, data, config);
  }

  async put<T>(endpoint: string, data?: any, config?: RequestConfig): Promise<T> {
    return this.request<T>('PUT', endpoint, data, config);
  }

  async patch<T>(endpoint: string, data?: any, config?: RequestConfig): Promise<T> {
    return this.request<T>('PATCH', endpoint, data, config);
  }

  async delete<T>(endpoint: string, config?: RequestConfig): Promise<T> {
    return this.request<T>('DELETE', endpoint, undefined, config);
  }
}

export const apiClient = new ApiWrapper();
