import { create } from 'zustand';
import { handleStoreRequest } from './utils/ApiHandler';
import AuthService from '@/services/authService';
import { CartService } from '@/services/cartService';
import { apiClient } from '@/api/ApiWrapper';

export interface CartItem {
  id: number;
  name: string;
  price: number;
  imagePath: string;
  quantity: number;
  gallery: any;
}

export interface CartState {
  items: CartItem[];
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
}

export type CartStore = CartState & CartActions;

const initialState: Omit<CartState, 'itemCount'> = {
  items: [],
  totalPrice: 0,
  isLoading: false,
  error: null,
};

export const useCartStore = create<CartStore>((set, get) => ({
  ...initialState,
  itemCount: 0,

  fetchCart: async () => {
    await handleStoreRequest(
    set,
    get,
    () => CartService.fetchCart(),
    {
      onSuccess: (response) => {
        const items = response.items.map((item): CartItem => ({
        id: item.id,
        name: item.name,
        price: Number(item.price),
        imagePath: item.image_path,
        quantity: item.quantity,
        gallery: item.gallery || { images: [] },
      }));
      
      const totalPrice = parseFloat(response.total_price);
      const itemCount = items.reduce((sum, item) => sum + item.quantity, 0);
      return { items, totalPrice, itemCount };
      },
      errorMessage: 'Failed to fetch cart.'
    }
    );
    },

  addItem: async (dishId, quantity) => {
    return await handleStoreRequest(
      set,
      get,
      () => CartService.addItem(dishId, quantity),
      {
        onSuccess: () => {
          get().fetchCart();
          return {};
        },
        errorMessage: 'Failed to add item.',
      }
    );
  },
  updateItem: async (dishId, quantity) => {
    return await handleStoreRequest(
      set,
      get,
      () => CartService.updateItem(dishId, quantity),
      {
        onSuccess: () => {
          get().fetchCart();
          return {};
        },
        errorMessage: 'Failed to update item.',
      }
    );
  },
  removeItem: async (dishId) => {
    return await handleStoreRequest(
      set,
      get,
      () => CartService.removeItem(dishId),
      {
        onSuccess: () => {
          get().fetchCart();
          return {};
        },
        errorMessage: 'Failed to remove item.',
      }
    );
  },
  clearCart: async () => {
    return await handleStoreRequest(
      set,
      get,
      () => CartService.clearCart(),
      {
        onSuccess: () => {
          get().fetchCart();
          return {};
        },
        errorMessage: 'Failed to clear cart.',
      }
    );
  },
  clearLocalCart: () => {
    set({ ...initialState, itemCount: 0 });
  },
}));