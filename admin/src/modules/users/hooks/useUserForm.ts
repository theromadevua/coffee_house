// src/modules/admin/hooks/useUserForm.ts

import { useState, useEffect, useCallback } from 'react';
import { useUserStore } from '../../../store/userStore';
import { IUser } from '../../shared/interfaces/user/IUser';
import { Role } from '../../shared/enums/users/Role';

interface UseUserFormProps {
  userToEdit: IUser | null;
  onClose: () => void;
}

export const useUserForm = ({ userToEdit, onClose }: UseUserFormProps) => {
  const { createUser, updateUser } = useUserStore();
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [role, setRole] = useState<any>(Role.USER);
  const [password, setPassword] = useState('');
  const [error, setError] = useState<string | null>(null);

  const isEditMode = !!userToEdit;

  useEffect(() => {
    if (userToEdit) {
      setName(userToEdit.name || '');
      setEmail(userToEdit.email || '');
      setRole(userToEdit.role || Role.USER);
      setPassword(''); // Password should not be pre-filled
    } else {
      // Reset form for creation
      setName('');
      setEmail('');
      setRole(Role.USER);
      setPassword('');
    }
  }, [userToEdit]);

  const validateForm = useCallback(() => {
    if (!name || !email) {
      setError('Please fill in all required fields (name and email).');
      return false;
    }
    if (!isEditMode && !password) {
      setError('Password is required for new users.');
      return false;
    }
    setError(null);
    return true;
  }, [name, email, password, isEditMode]);

  const handleSubmit = useCallback(
    async (e: React.FormEvent) => {
        
      e.preventDefault();

      if (!validateForm()) {
        return;
      }

      try {
        if (isEditMode && userToEdit) {
          const updateData: Partial<IUser> = { name, email, role };
          if (password) {
            (updateData as any).password = password;
            (updateData as any).password_confirmation = password;
          }
          await updateUser(userToEdit.id, updateData);
        } else {
          const createData: Partial<IUser> = { name, email, role, password, password_confirmation: password };
          await createUser(createData);
        }
        onClose();
      } catch (err: any) {
        setError(err.message || 'Failed to save the user. Please try again.');
        console.error('User submission error:', err);
      }
    },
    [isEditMode, userToEdit, name, email, role, password, createUser, updateUser, onClose, validateForm]
  );

  return {
    name,
    email,
    password,
    role,
    isEditMode,
    error,
    setName,
    setEmail,
    setPassword,
    setRole,
    handleSubmit,
  };
};