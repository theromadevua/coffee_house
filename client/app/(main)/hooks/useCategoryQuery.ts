'use client';

import { useRouter, useSearchParams } from 'next/navigation';
import { useState, useEffect } from 'react';

export function useCategoryQuery() {
    const router = useRouter();
    const searchParams = useSearchParams();
    const initialCategory = searchParams.get('category');
    const [activeCategory, setActiveCategory] = useState<string | null>(initialCategory);

    useEffect(() => {
        setActiveCategory(initialCategory); 
    }, [initialCategory]);

    const toggleCategory = (categoryId: string) => {
        const newCategory = activeCategory === categoryId ? null : categoryId;
        setActiveCategory(newCategory);

        const params = new URLSearchParams();
        if (newCategory) {
            params.set('category', newCategory);
        }

        router.push(`?${params.toString()}`);
    };

    return {
        activeCategory,
        toggleCategory,
    };
}
