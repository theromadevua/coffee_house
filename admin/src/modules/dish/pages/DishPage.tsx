import React from 'react';
import styles from '../../shared/styles/adminPage.module.css';
import { useDishesAdmin } from '../hooks/useDishesAdmin';
import { SearchBlock } from '../../shared/components/SearchBlock/SearchBlock';
import { StatusDisplay } from '../../shared/components/StatusDisplay/StatusDisplay';
import { AdminTable } from '../../shared/components/AdminTable/AdminTable';
import { DishForm } from '../components/DishForm/DishForm';
import { ConfirmationDialog } from '../../shared/components/ConfirmationDialog/ConfirmationDialog';
import { dishAdminPageColumns } from '../consts/dishAdminTableColumns';

const DishAdminPage: React.FC = () => {
  const {
    dishes,
    isLoading,
    error,
    searchQuery,
    isFormOpen,
    handleDishSearch,
    isConfirmOpen,
    itemToEdit,
    itemToDelete,
    handleCreate,
    openEditForm,
    openDeleteConfirm,
    confirmDelete,
    setIsFormOpen,
    setIsConfirmOpen,
  } = useDishesAdmin();

  return (
    <div className={styles.container}>
      <SearchBlock
        value={searchQuery}
        onChange={handleDishSearch}
        onCreate={handleCreate}
        placeholder="Search by name or category"
        title="Search Dishes"
      />
      
      <StatusDisplay isLoading={isLoading} error={error} />

      {!isLoading && !error && dishes && (
        <AdminTable
          title={'Manage Dishes'}
          data={dishes}
          columns={dishAdminPageColumns}
          onEdit={openEditForm}
          onDelete={openDeleteConfirm}
        />
      )}

      <DishForm
        open={isFormOpen}
        onClose={() => setIsFormOpen(false)}
        dishToEdit={itemToEdit}
      />

      <ConfirmationDialog
        open={isConfirmOpen}
        onClose={() => setIsConfirmOpen(false)}
        onConfirm={confirmDelete}
        title="Confirm"
        message={`Are you sure you want to delete dish "${itemToDelete?.name}"?`}
      />
    </div>
  );
};

export default DishAdminPage;
