import { create } from 'zustand';
import { CategoryService } from '../services/categoryService';
import { handleStoreRequest } from './utils/ApiHandler';

interface Category {
  id: number;
  name: string;
  description?: string;
  created_at?: string;
  updated_at?: string;
}

interface CategoryState {
  categories: Category[];
  isLoading: boolean;
  error: string | null;
  fetchCategories: () => Promise<void>;
  createCategory: (category: { name: string; description?: string }) => Promise<void>;
  updateCategory: (id: number, category: { name?: string; description?: string }) => Promise<void>;
  deleteCategory: (id: number) => Promise<void>;
}

export const useCategoryStore = create<CategoryState>((set, get) => ({
  categories: [],
  isLoading: false,
  error: null,

  fetchCategories: async () => {
    await handleStoreRequest(set, get, () => CategoryService.fetchCategories(), {
      onSuccess: (data) => ({ categories: data }),
    });
  },

  createCategory: async (category) => {
    await handleStoreRequest(set, get, () => CategoryService.createCategory(category), {
      onSuccess: (newCategory, state) => ({
        categories: [...state.categories, newCategory],
      }),
    });
  },

  updateCategory: async (id, category) => {
    await handleStoreRequest(set, get, () => CategoryService.updateCategory(id, category), {
      onSuccess: (updatedCategory, state) => ({
        categories: state.categories.map((c) =>
          c.id === id ? { ...c, ...updatedCategory } : c
        ),
      }),
    });
  },

  deleteCategory: async (id) => {
    await handleStoreRequest(set, get, () => CategoryService.deleteCategory(id), {
      onSuccess: (_, state) => ({
        categories: state.categories.filter((c) => c.id !== id),
      }),
    });
  },
}));