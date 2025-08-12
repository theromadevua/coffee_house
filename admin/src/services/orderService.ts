import { apiClient } from '../api/ApiWrapper';
import { ICreateOrderData } from '../modules/shared/interfaces/order/ICreateOrderData';
import { IOrder } from '../modules/shared/interfaces/order/IOrder';
import { IUpdateOrderData } from '../modules/shared/interfaces/order/IUpdateOrderData';
import { IApiResponse } from '../modules/shared/interfaces/shared/IApiResponse';


export class OrderService {
  static async fetchOrders(): Promise<IOrder[]> {
    const response = await apiClient.get<IApiResponse<IOrder[]>>('/orders');
    return response.data;
  }
  
  static async getOrdersStatistics(): Promise<IOrder[]> {
    const response = await apiClient.get<IApiResponse<IOrder[]>>('/orders/getOrdersStatistics');
    return response.data;
  }

  static async fetchAllOrders(): Promise<IOrder[]> {
    const response = await apiClient.get<IApiResponse<IOrder[]>>('/orders/all');
    return response.data;
  }

  static async findOrder(id: number): Promise<IOrder> {
    const response = await apiClient.get<IApiResponse<IOrder>>(`/orders/${id}`);
    return response.data;
  }

  static async createOrder(orderData: ICreateOrderData): Promise<IOrder> {
    const response = await apiClient.post<IApiResponse<IOrder>>('/orders', orderData);
    return response.data;
  }

  static async updateOrder(id: number, orderData: IUpdateOrderData): Promise<IOrder> {
    const response = await apiClient.put<IApiResponse<IOrder>>(`/orders/${id}`, orderData);
    return response.data;
  }

  static async deleteOrder(id: number): Promise<void> {
    await apiClient.delete<IApiResponse<null>>(`/orders/${id}`);
  }

}