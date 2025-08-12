import { create } from 'zustand';
import { OrderService } from '../services/orderService';
import { handleStoreRequest } from './utils/ApiHandler';
import jsPDF from 'jspdf';
import { autoTable } from 'jspdf-autotable'
import { apiClient } from '@/api/ApiWrapper';
import AuthService from '@/services/authService';

interface OrderState {
  orders: Order[];
  currentOrder: Order | null; 
  isLoading: boolean;
  error: string | null;
  fetchOrders: () => Promise<void>;
  findOrder: (id: number) => Promise<void>;
  createOrder: (orderData: CreateOrderData) => Promise<Order | undefined>;
  updateOrder: (id: number, orderData: UpdateOrderData) => Promise<void>;
  deleteOrder: (id: number) => Promise<void>;
  downloadOrderPDF: (order: Order) => void;
}

export const useOrderStore = create<OrderState>((set, get) => ({
  orders: [],
  currentOrder: null,
  isLoading: false,
  error: null,

  fetchOrders: async () => {
    await handleStoreRequest(set, get, () => OrderService.fetchOrders(), {
      onSuccess: (data) => ({ orders: data }),
      errorAction: async () => await AuthService.me()
    });
  },

  findOrder: async (id: number) => {
    set({ currentOrder: null }); 
    await handleStoreRequest(set, get, () => OrderService.findOrder(id), {
      onSuccess: (data) => ({ currentOrder: data }),
    });
  },

  createOrder: async (orderData) => {
    orderData.total_amount = orderData.items.reduce(
        (acc, item) => acc + Number(item.price * item.quantity),
        0
    );

    await handleStoreRequest(set, get, () => OrderService.createOrder(orderData), {
      onSuccess: (data, state) => {
        if (data?.redirect_url) {
          window.location.href = data.redirect_url;
        }

        return ({
          
        })
      },
    });

    return undefined;
  },


  updateOrder: async (id, orderData) => {
    await handleStoreRequest(set, get, () => OrderService.updateOrder(id, orderData), {
      onSuccess: (updatedOrder, state) => {
        return {
          orders: state.orders.map((o) =>
            o.id === id ? { ...o, ...updatedOrder } : o
          ),
          currentOrder: state.currentOrder?.id === id ? { ...state.currentOrder, ...updatedOrder } : state.currentOrder,
        };
      },
    });
  },

  deleteOrder: async (id) => {
    await handleStoreRequest(set, get, () => OrderService.deleteOrder(id), {
      onSuccess: (_, state) => ({
        orders: state.orders.filter((o) => o.id !== id),
        currentOrder: state.currentOrder?.id === id ? null : state.currentOrder,
      }),
    });
  },

  downloadOrderPDF: async (order: Order) => {
    const response: any = await fetch(`http://coffee.test/api/orders/download/${order.id}`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('accessToken')}`, 
      },
    });

    const blob = await response.blob();
    const url = window.URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = `order_${order.id}.pdf`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    window.URL.revokeObjectURL(url);
  }

}));