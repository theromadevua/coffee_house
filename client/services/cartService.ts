import { apiClient, ApiError } from '../api/ApiWrapper';

interface CartItem {
  id: number;
  name: string;
  price: number;
  image_path: string;
  quantity: number;
  gallery: any;
}

interface ApiCartResponse {
    items: CartItem[];
    total_price: string;
}

interface ApiResponse<T> {
  data: T;
  message: string;
  status: number;
}

export class CartService {
  static async fetchCart(): Promise<ApiCartResponse> {
    const response = await apiClient.get<ApiResponse<ApiCartResponse>>('/cart');
    return response.data;
  }

  static async addItem(dishId: number, quantity: number): Promise<void> {
    await apiClient.post<ApiResponse<null>>('/cart', { dish_id: dishId, quantity });
  }

  static async updateItem(dishId: number, quantity: number): Promise<void> {
    await apiClient.put<ApiResponse<null>>(`/cart/${dishId}`, { quantity });
  }

  static async removeItem(dishId: number): Promise<void> {
    await apiClient.delete<ApiResponse<null>>(`/cart/${dishId}`);
  }

  static async clearCart(): Promise<void> {
    await apiClient.delete<ApiResponse<null>>('/cart');
  }
}