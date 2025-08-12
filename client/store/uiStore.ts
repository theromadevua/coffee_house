import { create } from 'zustand';

interface UIState {
  profileImageWindow: boolean;
  toggleProfileImageWindow: () => void;
}

export const useUIStore = create<UIState>((set) => ({
  profileImageWindow: false,
  toggleProfileImageWindow: () =>
    set((state) => {
      console.log('Toggling profileImageWindow to:', !state.profileImageWindow);
      return { profileImageWindow: !state.profileImageWindow };
    }),
}));