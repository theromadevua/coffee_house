import React from 'react';
import styles from '../../shared/styles/adminPage.module.css';
import { useCategoryAdmin } from '../hooks/useCategoryAdmin';
import { SearchBlock } from '../../shared/components/SearchBlock/SearchBlock';
import { StatusDisplay } from '../../shared/components/StatusDisplay/StatusDisplay';
import { AdminTable } from '../../shared/components/AdminTable/AdminTable';
import { CategoryForm } from '../components/CategoryForm/CategoryForm';
import { ConfirmationDialog } from '../../shared/components/ConfirmationDialog/ConfirmationDialog';
import { categoryAdminTableColumns } from '../consts/categoryAdminTableColumns';

const CategoryAdminPage: React.FC = () => {
  const {
    isLoading,
    error,
    isFormOpen,
    isConfirmOpen,
    itemToEdit,
    itemToDelete,
    setIsFormOpen,
    setIsConfirmOpen,
    openCreateForm,
    openEditForm,
    openDeleteConfirm,
    confirmDelete,
    searchQuery,
    handleCategorySearch,
    filteredCategories,
  } = useCategoryAdmin();

  return (
    <div className={styles.container}>
      <SearchBlock
        value={searchQuery}
        onChange={handleCategorySearch}
        onCreate={openCreateForm}
        placeholder="Search by name or description"
        title="Search Categories"
      />
      
      <StatusDisplay isLoading={isLoading} error={error} />

      {!isLoading && !error && (
        <AdminTable
          title={'Manage Categories'}
          data={filteredCategories}
          columns={categoryAdminTableColumns}
          onEdit={openEditForm}
          onDelete={openDeleteConfirm}
        />
      )}

      <CategoryForm
        open={isFormOpen}
        onClose={() => setIsFormOpen(false)}
        categoryToEdit={itemToEdit}
      />

      <ConfirmationDialog
        open={isConfirmOpen}
        onClose={() => setIsConfirmOpen(false)}
        onConfirm={confirmDelete}
        title="Confirm"
        message={`Are you sure you want to delete category "${itemToDelete?.name}"?`}
      />
    </div>
  );
};

export default CategoryAdminPage;