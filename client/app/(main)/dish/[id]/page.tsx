'use client'

import { DishDetails } from "./components/DishDetails/DishDetails";
import { useItemPage } from "./hooks/useDishDetails";
import styles from './itemPage.module.css';

const ItemPage = () => {
    const {
        dish,
        isLoading,
        error,
        quantity,
        isCartLoading,
        notification,
        handleQuantityChange,
        handleAddToCart,
        showRatingPopup,
        handleSetQuantity,
        handleShowRatingPopup,
        handleRateDish
    } = useItemPage();

    if (isLoading) return <div className={styles.itemPageLoading}>Loading...</div>;
    if (error) return <div className={styles.itemPageError}>Error: {error}</div>;
    if (!dish) return <div className={styles.itemPageNotFound}>Dish not found</div>;

    return (
        <DishDetails
            dish={dish}
            onSetQuantity={handleSetQuantity}
            quantity={quantity}
            isCartLoading={isCartLoading}
            notification={notification}
            onQuantityChange={handleQuantityChange}
            onAddToCart={handleAddToCart}
            showRatingPopup={showRatingPopup}
            onShowRatingPopup={handleShowRatingPopup}
            onRateDish={handleRateDish}
        />
    );
};

export default ItemPage;
