'use client'
import useAuthStore from '@/store/authStore';
import { useDishStore } from '@/store/dishStore';
import { ImageSlider } from '../ImageSlider/ImageSlider';
import styles from './dishDetails.module.css';
import { RatingPopup } from '../RatingPopup/RatingPopup';
interface Props {
  dish: any;
  quantity: number;
  isCartLoading: boolean;
  notification: string | null;
  onQuantityChange: (delta: number) => void;
  onAddToCart: () => void;
  onSetQuantity: (value: number) => void;
  showRatingPopup: boolean;
  onShowRatingPopup: (show: boolean) => void;
  onRateDish: (rating: number) => Promise<void>;
}
export const DishDetails = ({ 
  dish, 
  quantity, 
  isCartLoading, 
  notification, 
  onQuantityChange, 
  onAddToCart, 
  onSetQuantity, 
  showRatingPopup, 
  onShowRatingPopup, 
  onRateDish 
}: Props) => {
  const { isAuthenticated } = useAuthStore();
  
  return (
    <div className={styles.itemPage}>
      {notification && <div className={styles.notification}>{notification}</div>}
      <div className={styles.itemContainer}>
        <div className={styles.itemImageContainer}>
          <ImageSlider
            images={dish?.gallery?.images}
            alt={dish.name ?? 'Dish'}
          />
        </div>
        <div className={styles.itemDetails}>
          <h1 className={styles.itemTitle}>{dish.name}</h1>
          <div className={styles.itemCategoryContainer}>
            <p className={styles.itemCategory}>
              Category: {dish.category?.name ?? 'Uncategorized'}
            </p>
            {dish.category?.description && (
              <p className={styles.itemCategoryDescription}>
                {dish.category.description}
              </p>
            )}
          </div>
          <p className={styles.itemDescription}>{dish.description ?? 'No description available'}</p>
          <p className={styles.itemPrice}>Price: ${dish.price ?? '0.00'}</p>
          <p className={styles.itemRating}>
            Average Rating: {dish.ratings_avg_rating ? `${Number(dish.ratings_avg_rating).toFixed(1)} / 5` : 'No ratings yet'}
            </p>
            {isAuthenticated && (
            <div className={styles.ratingSection}>
                <p className={styles.ratingStatus}>
                {dish.isRated ? <>You already rated this dish as: <b>{dish.rating}</b></> : 'You haven’t rated this dish yet'}
                </p>
                <button
                onClick={() => onShowRatingPopup(true)}
                className={styles.rateButton}
                >
                {dish.isRated ? 'Update Your Rating' : 'Rate Dish'}
                </button>
            </div>
            )}
          <div className={styles.cartActions}>
            <div className={styles.quantityControl}>
              <button
                className={styles.quantityButton}
                onClick={() => onQuantityChange(-1)}
                disabled={quantity <= 1}
              >
                -
              </button>
              <input
                className={styles.quantityDisplay}
                value={quantity.toString()}
                onChange={(e) => {
                  const rawValue = e.target.value.replace(/\D/g, '');
                  let value = Number(rawValue);
                  if (rawValue === '') {
                    onSetQuantity(0);
                    return;
                  }
                  if (value > 30) value = 30;
                  onSetQuantity(value);
                }}
              />
              <button
                className={styles.quantityButton}
                onClick={() => onQuantityChange(1)}
              >
                +
              </button>
            </div>
            <button
              className={styles.addToCartButton}
              onClick={onAddToCart}
              disabled={!isAuthenticated || isCartLoading}
            >
              {isCartLoading ? 'Adding...' : 'Add to Cart'}
            </button>
          </div>
          <p className={styles.itemCreated}>
            Added: {dish.created_at
              ? new Date(dish.created_at).toLocaleDateString()
              : 'Unknown date'}
          </p>
        </div>
      </div>
      {showRatingPopup && (
        <RatingPopup
            dishId={dish.id}
            onClose={() => onShowRatingPopup(false)}
            onSubmit={onRateDish}
        />
       )}
    </div>
  );
};