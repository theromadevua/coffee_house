import { apiClient } from '@/api/ApiWrapper';

export interface LoginCredentials {
  email: string;
  password: string;
}

export interface RegisterData {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
}

export interface AuthTokens {
  access_token: string;
  token_type: string;
  expires_in: number;
  refresh_token?: string;
}

export interface ApiResponse<T> {
  success: boolean;
  message: string;
  data: T;
}

export class AuthService {
  private static readonly AUTH_PREFIX = '/auth';

  static async register(userData: RegisterData): Promise<ApiResponse<User>> {
    return await apiClient.post<ApiResponse<User>>(
      `${this.AUTH_PREFIX}/register`,
      userData,
      { withCredentials: true }
    );
  }

  static async login(credentials: LoginCredentials): Promise<AuthTokens> {
    const response = await apiClient.post<ApiResponse<AuthTokens>>(
      `${this.AUTH_PREFIX}/login`,
      credentials,
      { withCredentials: true }
    );

    if (response.data.access_token) {
      localStorage.setItem('accessToken', response.data.access_token);
    }

    return response.data;
  }

  static async refresh(): Promise<AuthTokens> {
    const response = await apiClient.post<ApiResponse<AuthTokens>>(
      `${this.AUTH_PREFIX}/refresh`,
      {},
      { withCredentials: true }
    );

    if (response.data.access_token) {
      localStorage.setItem('accessToken', response.data.access_token);
    }

    return response.data;
  }

  static async addImage(image: File): Promise<User> {
    const formData = new FormData();
    formData.append('image', image);
    formData.append('name', 'aboba');

    console.log('Authservice', formData);
    const response = await apiClient.post<ApiResponse<User>>(
      `${this.AUTH_PREFIX}/addImage`,
      formData,
      {
        // headers: {
        //   'Content-Type': 'multipart/form-data',
        // },
        withCredentials: true,
      }
    );

    return response.data;
  }

  static async deleteImage(imageId: number): Promise<User> {
    const response = await apiClient.delete<ApiResponse<User>>(
      `${this.AUTH_PREFIX}/deleteImage/${imageId}`,
      { withCredentials: true }
    );
    return response.data;
  }


  static async logout(): Promise<null> {
    const response = await apiClient.delete<ApiResponse<null>>(
      `${this.AUTH_PREFIX}/logout`,
      { withCredentials: true }
    );

    localStorage.removeItem('accessToken');

    return null;
  }

  static async me(): Promise<User> {
    const response = await apiClient.get<ApiResponse<User>>(
      `${this.AUTH_PREFIX}/me`,
      { withCredentials: true }
    );
    return response.data;
  }

  static async updateProfile(userData: any): Promise<User> {
    const sanitizedUserData = { ...userData };
    
    if (sanitizedUserData.password == "") {
      delete sanitizedUserData.password;
    }

    if (sanitizedUserData.password_confirmation == '') {
      delete sanitizedUserData.password_confirmation;
    }

    const response = await apiClient.post<ApiResponse<User>>(
      `${this.AUTH_PREFIX}/profile`,
      sanitizedUserData,
      { withCredentials: true }
    );
  
    return response.data;
  }

  static async promoteToAdmin(userId: string): Promise<User> {
    const response = await apiClient.post<ApiResponse<User>>(
      `${this.AUTH_PREFIX}/promote-admin/${userId}`,
      {},
      { withCredentials: true }
    );
    return response.data;
  }

  static async checkAuth(): Promise<boolean> {
    const response = await apiClient.get<ApiResponse<User>>(
      `${this.AUTH_PREFIX}/me`,
      { withCredentials: true }
    );
    return response.success
  }

  static setAuthToken(token: string): void {
    localStorage.setItem('accessToken', token);
  }

  static removeAuthToken(): void {
    localStorage.removeItem('accessToken');
  }

  static getStoredToken(): string | null {
    return localStorage.getItem('accessToken');
  }
}

export default AuthService;
