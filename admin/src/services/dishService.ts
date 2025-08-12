import { ApiWrapper, RequestConfig } from '../api/ApiWrapper';
import { IDish } from '../modules/shared/interfaces/dish/IDish';
import { IApiResponse } from '../modules/shared/interfaces/shared/IApiResponse';

export class DishService {
  private api: ApiWrapper;

  constructor(api: ApiWrapper) {
    this.api = api;
  }

  async fetchDishes(category?: string, page?: number): Promise<any> {
        const url = category
      ? `/dishes?category=${encodeURIComponent(category)}&page=${page}`
      : `/dishes?page=${page}`;
      const response = await this.api.get<IApiResponse<any>>(url);
      return response.data;
  }
  
  async fetchDishById(id: number): Promise<IDish> {
      const response = await this.api.get<IApiResponse<IDish>>(`/dishes/${id}`);
      return response.data;
  }

  async createDish(dishData: FormData): Promise<IDish> {
      const config: RequestConfig = {
        headers: { 'Content-Type': 'multipart/form-data' },
      };
      const response = await this.api.post<IApiResponse<IDish>>('/dishes', dishData, config);
      return response.data;
  }

  async updateDish(id: number, dishData: FormData): Promise<IDish> {
      const config: RequestConfig = {
        headers: { 'Content-Type': 'multipart/form-data' },
      };
      const response = await this.api.post<IApiResponse<IDish>>(`/dishes/${id}`, dishData, config);
      return response.data;
  }

  async search(searchParams: string): Promise<any> {
    const response = await this.api.get<IApiResponse<any>>(`/dishes/?searchParams=${searchParams}`);
    return response.data;
}

  async deleteDish(id: number): Promise<void> {
      await this.api.delete(`/dishes/${id}`);
  }
}