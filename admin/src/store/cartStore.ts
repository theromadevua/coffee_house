import { create } from 'zustand';
import { apiClient, ApiError } from '../api/ApiWrapper'; 
import { handleStoreRequest } from './utils/ApiHandler'; 
import { ICartItem } from '../modules/shared/interfaces/cart/ICartItem';

export interface CartState {
  carts: [];
  items: ICartItem[];
  totalPrice: number;
  isLoading: boolean;
  error: string | null;
  itemCount: number; 
}

export interface CartActions {
  fetchCart: () => Promise<void>;
  addItem: (dishId: number, quantity: number) => Promise<{ success: boolean }>;
  updateItem: (dishId: number, quantity: number) => Promise<{ success: boolean }>;
  removeItem: (dishId: number) => Promise<{ success: boolean }>;
  clearCart: () => Promise<{ success: boolean }>;
  clearLocalCart: () => void; 
  getCartById: (id: string) => void;
  fetchAllCarts: () => {};
}

export type CartStore = CartState & CartActions;

interface ApiCartItem {
  id: number;
  name: string;
  price: number; 
  image_path: string;
  quantity: number;
  gallery: any;
}

interface ApiCartResponse {
  data: {
    items: ApiCartItem[];
    total_price: string; 
  };
}

const initialState: Omit<CartState, 'itemCount'> = {
  items: [],
  totalPrice: 0,
  isLoading: false,
  error: null,
  carts: []
};

export const useCartStore = create<CartStore>((set, get) => ({
  ...initialState,
  itemCount: 0,

  fetchAllCarts: async () => {
    await handleStoreRequest(
      set,
      get,
      () => apiClient.get<any>(`/cart/all`),
      {
        onSuccess: (response) => {
          return { carts: response.data };
        },
        errorMessage: 'Failed to fetch carts.',
      }
    );
  },

  getCartById: async (id) => {
    await handleStoreRequest(
      set,
      get,
      () => apiClient.get<ApiCartResponse>(`/cart/${id}`),
      {
        onSuccess: (response) => {
          const items = response.data.items.map((item): ICartItem => ({
            id: item.id,
            name: item.name,
            price: Number(item.price),
            imagePath: item.image_path,
            quantity: item.quantity,
            gallery: item.gallery || { images: [] },
          }));

          const totalPrice = parseFloat(response.data.total_price);
          const itemCount = items.reduce((sum, item) => sum + item.quantity, 0);

          return { items, totalPrice, itemCount };
        },
        errorMessage: 'Failed to fetch carts.',
      }
    );
  },

  fetchCart: async () => {
    await handleStoreRequest(
      set,
      get,
      () => apiClient.get<ApiCartResponse>('/cart'),
      {
        onSuccess: (response) => {
          const items = response.data.items.map((item): ICartItem => ({
            id: item.id,
            name: item.name,
            price: Number(item.price),
            imagePath: item.image_path,
            quantity: item.quantity,
            gallery: item.gallery || { images: [] },
          }));

          const totalPrice = parseFloat(response.data.total_price);
          const itemCount = items.reduce((sum, item) => sum + item.quantity, 0);

          return { items, totalPrice, itemCount };
        },
        errorMessage: 'Failed to fetch cart.',
      }
    );
  },

  addItem: async (dishId, quantity) => {
    set({ isLoading: true, error: null });
    try {
      await apiClient.post('/cart', { dish_id: dishId, quantity });
      await get().fetchCart(); 
      return { success: true };
    } catch (error) {
      const message = error instanceof ApiError ? error.message : 'Failed to add item.';
      set({ isLoading: false, error: message });
      return { success: false };
    }
  },

  updateItem: async (dishId, quantity) => {
    set({ isLoading: true, error: null });
    try {
      await apiClient.put(`/cart/${dishId}`, { quantity });
      await get().fetchCart(); 
      return { success: true };
    } catch (error) {
      const message = error instanceof ApiError ? error.message : 'Failed to update item.';
      set({ isLoading: false, error: message });
      return { success: false };
    }
  },

  removeItem: async (dishId) => {
    set({ isLoading: true, error: null });
    try {
      await apiClient.delete(`/cart/${dishId}`);
      await get().fetchCart(); 
      return { success: true };
    } catch (error) {
      const message = error instanceof ApiError ? error.message : 'Failed to remove item.';
      set({ isLoading: false, error: message });
      return { success: false };
    }
  },

  clearCart: async () => {
    set({ isLoading: true, error: null });
    try {
      await apiClient.delete('/cart'); 
      await get().fetchCart(); 
      return { success: true };
    } catch (error) {
      const message = error instanceof ApiError ? error.message : 'Failed to clear cart.';
      set({ isLoading: false, error: message });
      return { success: false };
    }
  },

 
  clearLocalCart: () => {
    set({ ...initialState, itemCount: 0 });
  },
}));