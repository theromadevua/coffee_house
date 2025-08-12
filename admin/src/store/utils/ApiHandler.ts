import { ApiError } from '../../api/ApiWrapper';

type StoreSet<S> = (updater: Partial<S> | ((state: S) => Partial<S>)) => void;
type StoreGet<S> = () => S;

interface BaseState {
  isLoading: boolean;
  error: string | null;
}

interface HandleRequestOptions<S, T> {
  onSuccess?: (data: T, state: S) => Partial<S>;
  errorMessage?: string;
}

export const handleStoreRequest = async <S extends BaseState, T>(
  set: StoreSet<S>,
  get: StoreGet<S>,
  apiCall: () => Promise<T>,
  options?: HandleRequestOptions<S, T>
): Promise<{ success: boolean; data?: T; message?: string }> => {
  set({ isLoading: true, error: null } as Partial<S>);

  try {
    const data = await apiCall();

    set((state) => ({
      ...state,
      isLoading: false,
      ...(options?.onSuccess ? options.onSuccess(data, get()) : {}),
    }));

    return { success: true, data };
  } catch (error) {
    const message =
      (error instanceof ApiError ? error.message : options?.errorMessage) ||
      'An unexpected error occurred';

    set({ isLoading: false, error: message } as Partial<S>);
    return { success: false, message };
  }
};