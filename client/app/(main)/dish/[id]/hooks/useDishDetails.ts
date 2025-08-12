import { useEffect, useState } from "react";
import { useParams } from "next/navigation";
import { useDishStore } from "@/store/dishStore";
import { useCartStore } from "@/store/cartStore";


export const useItemPage = () => {
    const params = useParams();
    const { fetchDishById, selectedDish, isLoading, error, rateDish } = useDishStore();
    const { addItem, isLoading: isCartLoading } = useCartStore();
    const [quantity, setQuantity] = useState(1);
    const [notification, setNotification] = useState<string | null>(null);
    const [showRatingPopup, setShowRatingPopup] = useState(false);
    useEffect(() => {
        if (params.id && typeof params.id === 'string') {
            fetchDishById(Number(params.id));
        }
    }, [params.id, fetchDishById]);
    const handleQuantityChange = (delta: number) => {
        setQuantity(prev => {
            const newQuantity = prev + delta;
            if (newQuantity < 1) return 1;
            if (newQuantity > 30) return 30;
            return newQuantity;
        });
    };
    const handleSetQuantity = (value: number) => {
        setQuantity(value);
    };
    const handleAddToCart = async () => {
        if (!selectedDish) return;
        const { success } = await addItem(selectedDish.id, quantity);
        if (success) {
            setNotification(`${selectedDish.name} (x${quantity}) added to cart!`);
            setTimeout(() => setNotification(null), 3000);
        }
    };
    const handleRateDish = async (rating: number) => {
        await rateDish(selectedDish?.id ?? 0, rating);
        setShowRatingPopup(false);
    };
    return {
        dish: selectedDish,
        isLoading,
        error,
        quantity,
        isCartLoading,
        notification,
        handleQuantityChange,
        handleAddToCart,
        handleSetQuantity,
        showRatingPopup,
        handleShowRatingPopup: setShowRatingPopup,
        handleRateDish
    };
};