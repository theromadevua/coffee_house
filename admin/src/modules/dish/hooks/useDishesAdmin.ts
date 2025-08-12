import { useEffect } from 'react';
import { useDishStore } from '../../../store/dishStore';
import { IDish } from '../../shared/interfaces/dish/IDish';
import { useSearch } from '../../shared/hooks/useSearch';
import { useAdmin } from '../../shared/hooks/useAdmin';

export const useDishesAdmin = () => {
  const { dishes, isLoading, error, fetchDishes, deleteDish, searchDishes } = useDishStore();

  useEffect(() => {
    fetchDishes();
  }, [fetchDishes]);

  const { searchQuery, handleSearch } = useSearch<IDish>({
    data: dishes, 
    searchKeys: [], 
    onServerSearch: searchDishes,
  });

  const { openCreateForm, ...crudHandlers } = useAdmin<IDish>(deleteDish);

  return {
    dishes,
    isLoading,
    error,
    searchQuery,
    handleDishSearch: handleSearch,
    handleCreate: openCreateForm, 
    ...crudHandlers,
  };
};