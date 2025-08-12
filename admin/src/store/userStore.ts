import { create } from 'zustand';
import { apiClient, ApiError } from '../api/ApiWrapper';
import { handleStoreRequest } from './utils/ApiHandler';
// import { Role } from '../enums/Role'; // Assuming Role enum is defined
import { IUser } from '../modules/shared/interfaces/user/IUser';

export interface UserState {
  users: IUser[];
  currentUser: IUser | null;
  isLoading: boolean;
  error: string | null;
}

export interface UserActions {
  fetchUsers: () => Promise<void>;
  fetchUser: (id: number) => Promise<void>;
  createUser: (userData: Partial<IUser>) => Promise<{ success: boolean }>;
  updateUser: (id: number, userData: Partial<IUser>) => Promise<{ success: boolean }>;
  deleteUser: (id: number) => Promise<{ success: boolean }>;
  clearError: () => void;
}

export type UserStore = UserState & UserActions;

interface ApiUserResponse {
  data: any
//   {
//     id: number;
//     name: string;
//     email: string;
//     role: Role;
//     email_verified_at?: string;
//   };
}

const initialState: UserState = {
  users: [],
  currentUser: null,
  isLoading: false,
  error: null,
};

export const useUserStore = create<UserStore>((set, get) => ({
  ...initialState,

  fetchUsers: async () => {
    await handleStoreRequest(
      set,
      get,
      () => apiClient.get<ApiUserResponse>('/users'),
      {
        onSuccess: (response) => {
          const users = response.data.map((user: IUser) => ({
            id: user.id,
            name: user.name,
            email: user.email,
            role: user.role,
            // emailVerifiedAt: user.email_verified_at ? new Date(user.email_verified_at) : undefined,
          }));
          return { users };
        },
        errorMessage: 'Failed to fetch users.',
      }
    );
  },

  fetchUser: async (id) => {
    await handleStoreRequest(
      set,
      get,
      () => apiClient.get<ApiUserResponse>(`/users/${id}`),
      {
        onSuccess: (response) => {
          const user: IUser = {
            id: response.data.id,
            name: response.data.name,
            email: response.data.email,
            role: response.data.role,
            created_at: response.data.created_at,
            updated_at: response.data.updated_at

            //email_verified_at: response.data.email_verified_at ? new Date(response.data.email_verified_at) : undefined,
          };
          return { currentUser: user };
        },
        errorMessage: 'Failed to fetch user.',
      }
    );
  },

  createUser: async (userData) => {
    set({ isLoading: true, error: null });
    try {
      await apiClient.post('/users', userData);
      await get().fetchUsers();
      return { success: true };
    } catch (error) {
      const message = error instanceof ApiError ? error.message : 'Failed to create user.';
      set({ isLoading: false, error: message });
      return { success: false };
    }
  },

  updateUser: async (id, userData) => {
    set({ isLoading: true, error: null });
    try {
      await apiClient.put(`/users/${id}`, userData);
      await get().fetchUsers();
      return { success: true };
    } catch (error) {
      const message = error instanceof ApiError ? error.message : 'Failed to update user.';
      set({ isLoading: false, error: message });
      return { success: false };
    }
  },

  deleteUser: async (id) => {
    set({ isLoading: true, error: null });
    try {
      await apiClient.delete(`/users/${id}`);
      await get().fetchUsers();
      return { success: true };
    } catch (error) {
      const message = error instanceof ApiError ? error.message : 'Failed to delete user.';
      set({ isLoading: false, error: message });
      return { success: false };
    }
  },

  clearError: () => {
    set({ error: null });
  },
}));