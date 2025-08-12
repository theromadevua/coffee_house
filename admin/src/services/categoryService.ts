import { apiClient, ApiError } from '../api/ApiWrapper';
import { ICategory } from '../modules/shared/interfaces/category/ICategory';
import { IApiResponse } from '../modules/shared/interfaces/shared/IApiResponse';

export class CategoryService {
  static async fetchCategories(): Promise<any> {
      const response = await apiClient.get<IApiResponse<ICategory[]>>('/categories');
      return response.data;
  }

  static async createCategory(category: { name: string; description?: string }): Promise<ICategory> {
      const response = await apiClient.post<IApiResponse<ICategory>>('/categories', category);
      return response.data;
  }

  static async updateCategory(id: number, category: { name?: string; description?: string }): Promise<ICategory> {
      const response = await apiClient.post<IApiResponse<ICategory>>(`/categories/${id}`, category);
      return response.data;
  }

  static async deleteCategory(id: number): Promise<void> {
      await apiClient.delete<IApiResponse<null>>(`/categories/${id}`);
  }
}