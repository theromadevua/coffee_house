import { useState, useRef, FormEvent, ChangeEvent } from 'react';
import useAuthStore from '../../../store/authStore';

interface FormData {
  name?: string;
  email?: string;
  password?: string;
  password_confirmation?: string;
}

const useProfileUpdate = () => {
  const { user, updateProfile, addImage, deleteImage, isLoading, error, clearError } = useAuthStore();
  const [formData, setFormData] = useState<FormData>({
    name: user?.name || '',
    email: user?.email || '',
    password: '',
    password_confirmation: ''
  });
  const [isGalleryOpen, setIsGalleryOpen] = useState(false);
  const fileInputRef = useRef<HTMLInputElement | null>(null);

  const handleSubmit = async (e: FormEvent) => {
    e.preventDefault();
    clearError();
    
    try {
      const result = await updateProfile(formData);
    } catch (err) {
      console.error('Profile update error:', err);
    }
  };

  const handleChange = (e: ChangeEvent<HTMLInputElement>) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value,
    });
  };

  const handleImageUpload = async (e: ChangeEvent<HTMLInputElement>) => {
    if (e.target.files && e.target.files[0]) {
      clearError();
      try {
        const file = e.target.files[0];
        await addImage(file);
      } catch (err) {
        console.error('Image upload error:', err);
      }
    }
  };

  const handleImageDelete = async (imageId: number) => {
    if (window.confirm('Are you sure you want to delete this image?')) {
      clearError();
      try {
        await deleteImage(imageId);
      } catch (err) {
        console.error('Image deletion error:', err);
      }
    }
  };

  const handleImageIconClick = () => {
    if (user?.gallery?.images?.length > 0) {
      setIsGalleryOpen(true);
    } else {
      fileInputRef.current?.click();
    }
  };

  const handleAddImage = () => {
    fileInputRef.current?.click();
  };

  const handleCloseGallery = () => {
    setIsGalleryOpen(false);
  };

  return {
    user,
    formData,
    isLoading,
    error,
    isGalleryOpen,
    fileInputRef,
    handleChange,
    handleSubmit,
    handleImageUpload,
    handleImageDelete,
    handleImageIconClick,
    handleAddImage,
    handleCloseGallery
  };
};

export default useProfileUpdate;