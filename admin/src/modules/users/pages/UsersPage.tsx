
import React from 'react';
import styles from '../../shared/styles/adminPage.module.css';
import { SearchBlock } from '../../shared/components/SearchBlock/SearchBlock';
import { StatusDisplay } from '../../shared/components/StatusDisplay/StatusDisplay';
import { AdminTable } from '../../shared/components/AdminTable/AdminTable';
import { ConfirmationDialog } from '../../shared/components/ConfirmationDialog/ConfirmationDialog';
import { userAdminPageColumns } from '../consts/usersAdminTableColumns';
import { UserForm } from '../components/UserForm/UserForm';
import { useUserAdmin } from '../hooks/useUserAdmin';

const UsersAdminPage: React.FC = () => {
  const {
    isLoading,
    searchQuery,
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
    handleUserSearch,
    filteredUsers,
  } = useUserAdmin();

  return (
    <div className={styles.container}>
      <SearchBlock
        value={searchQuery}
        onChange={handleUserSearch}
        onCreate={openCreateForm}
        placeholder="Search by name, email, or role..."
        title="Search Users"
      />
      
      <StatusDisplay isLoading={isLoading} error={error} />

      {!isLoading && !error && (
        <AdminTable
          title={'Manage Users'}
          data={filteredUsers} 
          columns={userAdminPageColumns}
          onEdit={openEditForm}
          onDelete={openDeleteConfirm}
        />
      )}

      <UserForm
        open={isFormOpen}
        onClose={() => setIsFormOpen(false)}
        userToEdit={itemToEdit}
      />

      <ConfirmationDialog
        open={isConfirmOpen}
        onClose={() => setIsConfirmOpen(false)}
        onConfirm={confirmDelete}
        title="Confirm Deletion"
        message={`Are you sure you want to delete the user "${itemToDelete?.name}"? This action cannot be undone.`}
      />
    </div>
  );
};

export default UsersAdminPage;