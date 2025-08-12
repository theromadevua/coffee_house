import { apiClient } from '../api/ApiWrapper';
import { IAuthTokens } from '../modules/shared/interfaces/auth/IAuthTokens';
import { ILoginCredentials } from '../modules/shared/interfaces/auth/ILoginCredentials';
import { IRegisterData } from '../modules/shared/interfaces/auth/IRegisterData';
import { IApiResponse } from '../modules/shared/interfaces/shared/IApiResponse';
import { IUser } from '../modules/shared/interfaces/user/IUser';


export class AuthService {
  private static readonly AUTH_PREFIX = '/auth';

  static async register(userData: IRegisterData): Promise<IApiResponse<IUser>> {
    return await apiClient.post<IApiResponse<IUser>>(
      `${this.AUTH_PREFIX}/register`,
      userData,
      { withCredentials: true }
    );
  }

  static async login(credentials: ILoginCredentials): Promise<IAuthTokens> {
    const response = await apiClient.post<IApiResponse<IAuthTokens>>(
      `${this.AUTH_PREFIX}/login`,
      credentials,
      { withCredentials: true }
    );

    if (response.data.access_token) {
      localStorage.setItem('accessToken', response.data.access_token);
    }

    return response.data;
  }

  static async refresh(): Promise<IAuthTokens> {
    const response = await apiClient.post<IApiResponse<IAuthTokens>>(
      `${this.AUTH_PREFIX}/refresh`,
      {},
      { withCredentials: true }
    );

    if (response.data.access_token) {
      localStorage.setItem('accessToken', response.data.access_token);
    }

    return response.data;
  }

  static async addImage(image: File): Promise<IUser> {
    const formData = new FormData();
    formData.append('image', image);
    formData.append('name', 'aboba');

    console.log('Authservice', formData);
    const response = await apiClient.post<IApiResponse<IUser>>(
      `${this.AUTH_PREFIX}/addImage`,
      formData,
      {
        withCredentials: true,
      }
    );

    return response.data;
  }

  static async deleteImage(imageId: number): Promise<IUser> {
    const response = await apiClient.delete<IApiResponse<IUser>>(
      `${this.AUTH_PREFIX}/deleteImage/${imageId}`,
      { withCredentials: true }
    );
    return response.data;
  }


  static async logout(): Promise<null> {
    const response = await apiClient.delete<IApiResponse<null>>(
      `${this.AUTH_PREFIX}/logout`,
      { withCredentials: true }
    );

    localStorage.removeItem('accessToken');

    return null;
  }

  static async me(): Promise<IUser> {
    const response = await apiClient.get<IApiResponse<IUser>>(
      `${this.AUTH_PREFIX}/me`,
      { withCredentials: true }
    );
    return response.data;
  }

  static async updateProfile(userData: any): Promise<IUser> {
    const sanitizedUserData = { ...userData };
    
    if (sanitizedUserData.password == "") {
      delete sanitizedUserData.password;
    }

    if (sanitizedUserData.password_confirmation == '') {
      delete sanitizedUserData.password_confirmation;
    }

    const response = await apiClient.post<IApiResponse<IUser>>(
      `${this.AUTH_PREFIX}/profile`,
      sanitizedUserData,
      { withCredentials: true }
    );
  
    return response.data;
  }

  static async promoteToAdmin(userId: string): Promise<IUser> {
    const response = await apiClient.post<IApiResponse<IUser>>(
      `${this.AUTH_PREFIX}/promote-admin/${userId}`,
      {},
      { withCredentials: true }
    );
    return response.data;
  }

  static async checkAuth(): Promise<boolean> {
    const response = await apiClient.get<IApiResponse<IUser>>(
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
