'use client';

import { useRouter, useSearchParams } from 'next/navigation';
import { useCallback, useEffect, useState } from 'react';

export function useQueryManager() {
  const router = useRouter();
  const searchParams = useSearchParams();
  const [activeCategory, setActiveCategory] = useState<string | null>(searchParams.get('category'));
  const [page, setPage] = useState<number>(parseInt(searchParams.get('page') || '1', 10));

  useEffect(() => {
    setActiveCategory(searchParams.get('category'));
    setPage(parseInt(searchParams.get('page') || '1', 10));
  }, [searchParams]);

  const updateQuery = useCallback(
    (newCategory: string | null, newPage: number) => {
      const params = new URLSearchParams();
      if (newCategory) {
        params.set('category', newCategory);
      }
      if (newPage > 1) {
        params.set('page', newPage.toString());
      }
      router.push(`/?${params.toString()}`);
    },
    [router]
  );

  const toggleCategory = useCallback(
    (categoryId: string) => {
      const newCategory = activeCategory === categoryId ? null : categoryId;
      setActiveCategory(newCategory);
      setPage(1); 
      updateQuery(newCategory, 1);
    },
    [activeCategory, updateQuery]
  );

  const changePage = useCallback(
    (newPage: number) => {
      setPage(newPage);
      updateQuery(activeCategory, newPage);
    },
    [activeCategory, updateQuery]
  );

  const clearCategory = () => {
    setActiveCategory(null);
    router.push(`/`);
  }

  return {
    activeCategory,
    page,
    toggleCategory,
    changePage,
    clearCategory
  };
}