import { create } from 'zustand';
import { persist, createJSONStorage } from 'zustand/middleware';
import { AuthService } from '../services/authService';
import { handleStoreRequest } from './utils/ApiHandler';
import { IUser } from '../modules/shared/interfaces/user/IUser';
import { IRegisterData } from '../modules/shared/interfaces/auth/IRegisterData';
import { ILoginCredentials } from '../modules/shared/interfaces/auth/ILoginCredentials';

interface AuthState {
  user: IUser | null;
  isAuthenticated: boolean;
  isLoading: boolean;
  isInitialized: boolean;
  error: string | null;

  register: (userData: IRegisterData) => any;
  login: (credentials: ILoginCredentials) => any;
  logout: () => Promise<void>;
  initialize: () => Promise<void>;
  promoteToAdmin: (userId: string) => any;
  deleteImage: any,
  addImage: any,
  updateProfile: (userData: Partial<IUser>) => any;
  clearError: () => void;
}

const useAuthStore = create<AuthState>()(
  persist(
    (set, get) => ({
      user: null,
      isAuthenticated: false,
      isLoading: false,
      isInitialized: false,
      error: null,

      initialize: async () => {
        if (get().isInitialized) return;
        set({ isLoading: true, error: null });
        try {
          const userResponse = await AuthService.me();
          set({ user: userResponse, isAuthenticated: true, isInitialized: true, isLoading: false });
        } catch (e) {
          try {
            await AuthService.refresh();
            const userResponse = await AuthService.me();
            set({ user: userResponse, isAuthenticated: true, isInitialized: true, isLoading: false });
          } catch (error) {
            set({ user: null, isAuthenticated: false, isInitialized: true, isLoading: false });
            console.error('Auth initialization failed:', error);
          }
        }
      },

      register: (userData: IRegisterData) => {
        return handleStoreRequest(set, get, () => AuthService.register(userData), {
          onSuccess: (response: any) => ({
            user: response,
            isAuthenticated: true,
          }),
          errorMessage: 'Registration failed',
        });
      },

      login: (credentials: ILoginCredentials) => {
        const apiCall = async () => {
          await AuthService.login(credentials);
          return await AuthService.me();
        };
        return handleStoreRequest(set, get, apiCall, {
          onSuccess: (userResponse: any) => ({
            user: userResponse,
            isAuthenticated: true,
          }),
          errorMessage: 'Login failed',
        });
      },

      updateProfile: (userData: Partial<IUser>) => {
        return handleStoreRequest(set, get, () => AuthService.updateProfile(userData), {
          onSuccess: (response: any) => ({ user: response }),
          errorMessage: 'Profile update failed',
        });
      },

      addImage: (image: File) => {
        return handleStoreRequest(set, get, () => AuthService.addImage(image), {
          onSuccess: (response: any) => ({
            user: response 
          }),
          errorMessage: 'Image upload failed',
        });
      },

      deleteImage: (imageId: number) => {
        return handleStoreRequest(set, get, async () => {
          const currentUser = get().user;
          if (!currentUser) {
            throw new Error('No user is currently logged in');
          }
          
          await AuthService.deleteImage(imageId);
          const updatedUser: IUser = {
            ...currentUser,
            gallery: {
              ...currentUser.gallery,
              images: currentUser.gallery?.images?.filter((image: any) => image.id !== imageId) ?? [],
            },
          };
          set({ user: updatedUser });
        }, {
          errorMessage: 'Image deletion failed',
        });
      },

      logout: async () => {
        set({ isLoading: true });
        try {
          await AuthService.logout();
        } catch (error) {
          console.error('Logout request failed, but clearing session anyway.', error);
        } finally {
          localStorage.removeItem('accessToken')
          set({ user: null, isAuthenticated: false, isLoading: false, error: null });
        }
      },

      promoteToAdmin: (userId: string) => {
        return handleStoreRequest(set, get, () => AuthService.promoteToAdmin(userId), {
          errorMessage: 'Promotion failed',
        });
      },

      clearError: () => set({ error: null }),
    }),
    {
      name: 'auth-storage',
      storage: createJSONStorage(() => localStorage),
      partialize: (state) => ({
        user: state.user,
        isAuthenticated: state.isAuthenticated,
      }),
    }
  )
);

export default useAuthStore;