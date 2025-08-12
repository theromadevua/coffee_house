import { useState } from 'react';

interface IEntity {
  id: number | string;
}

export const useAdmin = <T extends IEntity>(deleteItem: (id: T['id']) => void) => {
  const [isFormOpen, setIsFormOpen] = useState(false);
  const [isConfirmOpen, setIsConfirmOpen] = useState(false);
  const [itemToEdit, setItemToEdit] = useState<T | null>(null);
  const [itemToDelete, setItemToDelete] = useState<T | null>(null);

  const openCreateForm = () => {
    setItemToEdit(null);
    setIsFormOpen(true);
  };

  const openEditForm = (item: T) => {
    setItemToEdit(item);
    setIsFormOpen(true);
  };

  const openDeleteConfirm = (item: T) => {
    setItemToDelete(item);
    setIsConfirmOpen(true);
  };

  const confirmDelete = () => {
    if (itemToDelete) {
      deleteItem(itemToDelete.id);
      setItemToDelete(null);
      setIsConfirmOpen(false);
    }
  };
  
  const closeForm = () => setIsFormOpen(false);
  const closeConfirm = () => setIsConfirmOpen(false);

  return {
    isFormOpen,
    isConfirmOpen,
    itemToEdit,
    itemToDelete,
    openCreateForm,
    openEditForm,
    openDeleteConfirm,
    confirmDelete,
    setIsFormOpen, 
    setIsConfirmOpen,
    closeForm,
    closeConfirm
  };
};