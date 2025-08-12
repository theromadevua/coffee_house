import { create } from 'zustand';
import { DishService } from '../services/dishService';
import { apiClient } from '../api/ApiWrapper';
import { handleStoreRequest } from './utils/ApiHandler';

interface Dish {
  id: number;
  name: string;
  description: string;
  price: number;
  image_path?: string;
  created_at?: string;
  category?: {
    id: number;
    description?: string;
    name: string;
  };
  ratings_avg_rating?: number;
  ratings_count?: number;
  isRated?: boolean;
}

interface DishState {
  dishes: Dish[];
  selectedDish: Dish | null;
  isLoading: boolean;
  error: string | null;
  currentPage: number;
  firstPageUrl: string;
  lastPageUrl: string;
  lastPage: number;
  fetchDishes: (category?: string, page?: number) => Promise<void>;
  fetchDishById: (id: number) => Promise<void>;
  createDish: (dishData: FormData) => Promise<void>;
  updateDish: (id: number, dishData: FormData) => Promise<void>;
  deleteDish: (id: number) => Promise<void>;
  searchDishes: (searchParams: string) => Promise<void>;
  rateDish: (id: number, rating: number) => Promise<void>;
}

const dishService = new DishService(apiClient);

export const useDishStore = create<DishState>((set, get) => ({
  dishes: [],
  selectedDish: null,
  isLoading: false,
  error: null,
  currentPage: 1,
  firstPageUrl: '',
  lastPageUrl: '',
  lastPage: 1,
  fetchDishes: async (category?: string, page: number = 1) => {
    await handleStoreRequest(set, get, () => dishService.fetchDishes(category, page), {
      onSuccess: (response) => ({
        dishes: response.data,
        currentPage: response.current_page,
        firstPageUrl: response.first_page_url,
        lastPageUrl: response.last_page_url,
        lastPage: response.last_page
      }),
    });
  },
  fetchDishById: async (id: number) => {
    await handleStoreRequest(set, get, () => dishService.fetchDishById(id), {
      onSuccess: (dish) => ({ selectedDish: dish }),
    });
  },
  createDish: async (dishData: FormData) => {
    await handleStoreRequest(set, get, () => dishService.createDish(dishData), {
      onSuccess: (newDish, state) => ({
        dishes: [...state.dishes, newDish],
      }),
    });
  },
  updateDish: async (id: number, dishData: FormData) => {
    await handleStoreRequest(set, get, () => dishService.updateDish(id, dishData), {
      onSuccess: (updatedDish, state) => ({
        dishes: state.dishes.map((dish) => (dish.id === id ? updatedDish : dish)),
        selectedDish: state.selectedDish?.id === id ? updatedDish : state.selectedDish,
      }),
    });
  },
  deleteDish: async (id: number) => {
    await handleStoreRequest(set, get, () => dishService.deleteDish(id), {
      onSuccess: (_, state) => ({
        dishes: state.dishes.filter((dish) => dish.id !== id),
        selectedDish: state.selectedDish?.id === id ? null : state.selectedDish,
      }),
    });
  },
  searchDishes: async (searchParams: string) => {
    await handleStoreRequest(set, get, () => dishService.search(searchParams), {
      onSuccess: (response) => ({
        dishes: response.data,
        currentPage: response.current_page,
        firstPageUrl: response.first_page_url,
        lastPageUrl: response.last_page_url,
        lastPage: response.last_page
      }),
    });
  },
  rateDish: async (id: number, rating: number) => {
    await handleStoreRequest(set, get, () => dishService.rateDish(id, rating), {
      onSuccess: (updatedDish, state) => ({
        dishes: state.dishes.map((dish) => (dish.id === id ? updatedDish : dish)),
        selectedDish: state.selectedDish?.id === id ? updatedDish : state.selectedDish,
      }),
    });
  },
}));