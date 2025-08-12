import { ApiWrapper, RequestConfig } from '../api/ApiWrapper';

interface Dish {
  id: number;
  name: string;
  description: string;
  price: number;
  image_path?: string;
  category?: {
    id: number;
    description?: string;
    name: string;
  };
}

interface ApiResponse<T> {
  data: T;
  message: string;
  status: number;
}

export class DishService {
  private api: ApiWrapper;

  constructor(api: ApiWrapper) {
    this.api = api;
  }

  async fetchDishes(category?: string, page?: number): Promise<any> {
        const url = category
      ? `/dishes?category=${encodeURIComponent(category)}&page=${page}`
      : `/dishes?page=${page}`;
      const response = await this.api.get<ApiResponse<any>>(url);
      return response.data;
  }

  
  async fetchDishById(id: number): Promise<Dish> {
      const response = await this.api.get<ApiResponse<Dish>>(`/dishes/${id}`);
      return response.data;
  }

  async createDish(dishData: FormData): Promise<Dish> {
      const config: RequestConfig = {
        headers: { 'Content-Type': 'multipart/form-data' },
      };
      const response = await this.api.post<ApiResponse<Dish>>('/dishes', dishData, config);
      return response.data;
  }

  async updateDish(id: number, dishData: FormData): Promise<Dish> {
      const config: RequestConfig = {
        headers: { 'Content-Type': 'multipart/form-data' },
      };
      const response = await this.api.put<ApiResponse<Dish>>(`/dishes/${id}`, dishData, config);
      return response.data;
  }

  async search(searchParams: string): Promise<any> {
    const response = await this.api.get<ApiResponse<any>>(`/dishes/?searchParams=${searchParams}`);
    return response.data;
  }

  async deleteDish(id: number): Promise<void> {
      await this.api.delete(`/dishes/${id}`);
  }

  async rateDish(id: number, rating: number) {
    const response = await this.api.post<ApiResponse<any>>(`/dishes/rate/${id}`, { rating });
    return response.data;
  }
}