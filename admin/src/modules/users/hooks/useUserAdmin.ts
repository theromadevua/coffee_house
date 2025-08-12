// src/modules/admin/hooks/useUserAdmin.ts

import { useEffect } from 'react';
import { useUserStore } from '../../../store/userStore';
import { IUser } from '../../shared/interfaces/user/IUser';
import { useSearch } from '../../shared/hooks/useSearch';
import { useAdmin } from '../../shared/hooks/useAdmin';

export const useUserAdmin = () => {
  const { users, isLoading, error, fetchUsers, deleteUser } = useUserStore();

  useEffect(() => {
    fetchUsers();
  }, [fetchUsers]);

  const { searchQuery, handleSearch, filteredData: filteredUsers } = useSearch<IUser>({
    data: users,
    searchKeys: ['name', 'email', 'role'], // Searchable fields
  });

  const crudHandlers = useAdmin<IUser>(deleteUser);

  return {
    isLoading,
    error,
    filteredUsers,
    searchQuery,
    handleUserSearch: handleSearch,
    ...crudHandlers,
  };
};