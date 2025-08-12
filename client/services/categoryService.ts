import { apiClient, ApiError } from '../api/ApiWrapper';

interface Category {
  id: number;
  name: string;
  description?: string;
  created_at?: string;
  updated_at?: string;
}

interface ApiResponse<T> {
  data: T;
  message: string;
  status: number;
}

export class CategoryService {
  static async fetchCategories(): Promise<any> {
      const response = await apiClient.get<ApiResponse<Category[]>>('/categories');
      return response.data;
  }

  static async createCategory(category: { name: string; description?: string }): Promise<Category> {
      const response = await apiClient.post<ApiResponse<Category>>('/categories', category);
      return response.data;
  }

  static async updateCategory(id: number, category: { name?: string; description?: string }): Promise<Category> {
      const response = await apiClient.post<ApiResponse<Category>>(`/categories/${id}`, category);
      return response.data;
  }

  static async deleteCategory(id: number): Promise<void> {
      await apiClient.delete<ApiResponse<null>>(`/categories/${id}`);
  }
}