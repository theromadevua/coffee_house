import useAuthStore from '@/store/authStore';
import { useCallback } from 'react';

export const useGoogleLogin = (
  onError?: (error: string) => void
) => {
    const {refresh} = useAuthStore()

  const handleGoogleLogin = useCallback(async () => {
    const width = 600;
    const height = 600;
    const left = window.screen.width / 2 - width / 2;
    const top = window.screen.height / 2 - height / 2;
    const googleLoginUrl = `${process.env.NEXT_PUBLIC_BACKEND_URL}/api/auth/login/google`;

    const popup = window.open(
      googleLoginUrl,
      'google_login',
      `width=${width},height=${height},top=${top},left=${left}`
    );

    const handleMessage = (event: MessageEvent) => {
      const {
        type,
        access_token,
        refresh_token,
        token_type,
        expires_in,
        error,
      } = event.data;

      if (type === 'google_login_success') {
        document.cookie = `refresh_token=${refresh_token}; path=/;`;
        localStorage.setItem('accessToken', access_token);
        localStorage.setItem('token_type', token_type);
        localStorage.setItem('expires_in', expires_in);
        refresh()
      } else if (type === 'google_login_error') {
        console.error('Google login failed:', error);
        onError?.(error);
      }
      window.removeEventListener('message', handleMessage);
    };

    window.addEventListener('message', handleMessage);

    const checkPopup = setInterval(() => {
      if (!popup || popup.closed) {
        clearInterval(checkPopup);
        window.removeEventListener('message', handleMessage);
      }
    }, 500);
  }, [onError]);

  return { handleGoogleLogin };
};