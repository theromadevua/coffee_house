import { apiClient } from '../api/ApiWrapper';

interface ApiResponse<T> {
  data: T;
  message: string;
}
export class OrderService {
  static async fetchOrders(): Promise<Order[]> {
    const response = await apiClient.get<ApiResponse<Order[]>>('/orders');
    return response.data;
  }

  static async findOrder(id: number): Promise<Order> {
    const response = await apiClient.get<ApiResponse<Order>>(`/orders/${id}`);
    return response.data;
  }

  static async createOrder(orderData: CreateOrderData): Promise<any> {
    const response = await apiClient.post<ApiResponse<any>>('/orders', orderData);
    return response.data;
  }

  static async updateOrder(id: number, orderData: UpdateOrderData): Promise<Order> {
    const response = await apiClient.put<ApiResponse<Order>>(`/orders/${id}`, orderData);
    return response.data;
  }

  static async deleteOrder(id: number): Promise<void> {
    await apiClient.delete<ApiResponse<null>>(`/orders/${id}`);
  }
}