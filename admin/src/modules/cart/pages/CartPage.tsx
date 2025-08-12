
import { AdminTable } from '../../shared/components/AdminTable/AdminTable';
import { ConfirmationDialog } from '../../shared/components/ConfirmationDialog/ConfirmationDialog';
import { SearchBlock } from '../../shared/components/SearchBlock/SearchBlock';
import { StatusDisplay } from '../../shared/components/StatusDisplay/StatusDisplay';
import styles from '../../shared/styles/adminPage.module.css';
import { cartAdminTableColumns } from '../consts/cartAdminTableColumns';
import useCartAdmin from '../hooks/useCartAdmin';

const CartAdminPage = () => {
  const {
    carts,
    isLoading,
    error,
    isConfirmOpen,
    cartToDelete,
    searchUserId,
    handleDelete,
    confirmDelete,
    handleCartSearch,
    setIsConfirmOpen
  } = useCartAdmin();

  return (
    <div className={styles.container}>
      <SearchBlock
        value={searchUserId}
        onChange={handleCartSearch}
        placeholder="Search by user id"
        title="Search Carts"
      />
      
      <StatusDisplay isLoading={isLoading} error={error} />

      {!isLoading && !error && (
        <AdminTable
          title="Manage Carts"
          data={carts}
          columns={cartAdminTableColumns}
          onDelete={handleDelete}
        />
      )}

      <ConfirmationDialog
        open={isConfirmOpen}
        onClose={() => setIsConfirmOpen(false)}
        onConfirm={confirmDelete}
        title="Confirm"
        message={`Are you sure you want to delete the cart for user ID "${cartToDelete?.user_id}"?`}
      />
    </div>
  );
};

export default CartAdminPage;