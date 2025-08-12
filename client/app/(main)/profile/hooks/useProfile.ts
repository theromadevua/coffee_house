import { useEffect, useState } from 'react';
import { useRouter } from 'next/navigation';
import useAuthStore from '@/store/authStore';
import { useUIStore } from '@/store/uiStore';

interface FormData {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
}

export const useProfile = () => {
  const { user, updateProfile, logout, isLoading, isInitialized, addImage, deleteImage } = useAuthStore();
  const { profileImageWindow, toggleProfileImageWindow } = useUIStore();
  const [formData, setFormData] = useState<FormData>({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
  });
  const [errors, setErrors] = useState<{ [key: string]: string }>({});
  const router = useRouter();

  useEffect(() => {
    if (user) {
      setFormData({
        name: user.name || '',
        email: user.email || '',
        password: '',
        password_confirmation: '',
      });
    }
  }, [user]);

  useEffect(() => {
    if (!user && !isLoading && isInitialized) {
      router.push('/auth/login');
    }
  }, [user, isLoading, isInitialized, router]);

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const { name, value } = e.target;
  
    setFormData((prev) => ({ ...prev, [name]: value }));
  
    setErrors((prev) => {
      const newErrors = { ...prev };
  
      // Очистка ошибки для текущего поля
      delete newErrors[name];
  
      // Валидация поля на лету
      if (name === 'name' && !value.trim()) {
        newErrors.name = 'Name is required';
      }
  
      if (name === 'email') {
        if (!value.trim()) {
          newErrors.email = 'Email is required';
        } else if (!/\S+@\S+\.\S+/.test(value)) {
          newErrors.email = 'Invalid email format';
        }
      }
  
      if (name === 'password' || name === 'password_confirmation') {
        const password = name === 'password' ? value : formData.password;
        const password_confirmation = name === 'password_confirmation' ? value : formData.password_confirmation;
  
        if (password || password_confirmation) {
          if (!password) {
            newErrors.password = 'Password is required if confirmation is provided';
          } else if (password.length < 8) {
            newErrors.password = 'Password must be at least 8 characters';
          }
  
          if (!password_confirmation) {
            newErrors.password_confirmation = 'Password confirmation is required if password is provided';
          } else if (password !== password_confirmation) {
            newErrors.password_confirmation = 'Passwords do not match';
          }
        } else {
          // Если оба пустые — убираем ошибки
          delete newErrors.password;
          delete newErrors.password_confirmation;
        }
      }
  
      return newErrors;
    });
  };
  

  const validate = (): boolean => {
    const newErrors: { [key: string]: string } = {};

    if (!formData.name.trim()) {
      newErrors.name = 'Name is required';
    }

    if (!formData.email.trim()) {
      newErrors.email = 'Email is required';
    } else if (!/\S+@\S+\.\S+/.test(formData.email)) {
      newErrors.email = 'Invalid email format';
    }

    if (formData.password || formData.password_confirmation) {
      if (!formData.password) {
        newErrors.password = 'Password is required if confirmation is provided';
      } else if (formData.password.length < 8) {
        newErrors.password = 'Password must be at least 8 characters';
      }

      if (!formData.password_confirmation) {
        newErrors.password_confirmation = 'Password confirmation is required if password is provided';
      } else if (formData.password !== formData.password_confirmation) {
        newErrors.password_confirmation = 'Passwords do not match';
      }
    }

    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleLogout = () => {
    logout();
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (validate()) {
      const payload: Partial<FormData> = {
        name: formData.name,
        email: formData.email,
      };
      if (formData.password) {
        payload.password = formData.password;
        payload.password_confirmation = formData.password_confirmation;
      }
      updateProfile(payload);
    }
  };

  return {
    user,
    formData,
    errors,
    isLoading,
    handleChange,
    handleLogout,
    handleSubmit,
    profileImageWindow,
    handleOpenProfileImage: toggleProfileImageWindow,
    deleteImage,
    addImage,
    isRedirecting: !user && !isLoading && isInitialized,
  };
};