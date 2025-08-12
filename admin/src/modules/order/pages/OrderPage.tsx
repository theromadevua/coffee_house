import React from 'react';
import styles from '../../shared/styles/adminPage.module.css';
import { useOrderAdmin } from '../hooks/useOrderAdmin';
import { SearchBlock } from '../../shared/components/SearchBlock/SearchBlock';
import { StatusDisplay } from '../../shared/components/StatusDisplay/StatusDisplay';
import { AdminTable } from '../../shared/components/AdminTable/AdminTable';
import { OrderForm } from '../components/OrderForm/OrderForm';
import { ConfirmationDialog } from '../../shared/components/ConfirmationDialog/ConfirmationDialog';
import { orderAdminPageColumns } from '../consts/orderAdminTableColumns';

const OrdersAdminPage: React.FC = () => {
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
    handleOrderSearch,
    filteredOrders
  } = useOrderAdmin();

  return (
    <div className={styles.container}>
      <SearchBlock
        value={searchQuery}
        onChange={handleOrderSearch}
        onCreate={openCreateForm}
        placeholder="Search by name or category"
        title="Search Dishes"
      />
      
      <StatusDisplay isLoading={isLoading} error={error} />

      {!isLoading && !error && (
        <AdminTable
          title={'Manage Orders'}
          data={filteredOrders} 
          columns={orderAdminPageColumns}
          onEdit={openEditForm}
          onDelete={openDeleteConfirm}
        />
      )}

      <OrderForm
        open={isFormOpen}
        onClose={() => setIsFormOpen(false)}
        orderToEdit={itemToEdit}
      />

      <ConfirmationDialog
        open={isConfirmOpen}
        onClose={() => setIsConfirmOpen(false)}
        onConfirm={confirmDelete}
        title="Confirm"
        message={`Are you sure you want to delete order #${itemToDelete?.id} for ${itemToDelete?.delivery_address || 'this address'}?`}
      />
    </div>
  );
};

export default OrdersAdminPage;