import { useState, useMemo } from 'react';

type SearchOptions<T> = {
  data: T[]; 
  searchKeys: (keyof T)[]; 
  onServerSearch?: (query: string) => void; 
};

export const useSearch = <T>({ data, searchKeys, onServerSearch }: SearchOptions<T>) => {
  const [searchQuery, setSearchQuery] = useState('');

  const handleSearch = (value: string) => {
    setSearchQuery(value);
    if (onServerSearch) {
      onServerSearch(value);
    }
  };

  const filteredData = useMemo(() => {
    if (onServerSearch) {
      return data;
    }

    if (!searchQuery.trim()) {
      return data;
    }

    const lowercasedQuery = searchQuery.toLowerCase();

    return data.filter((item) =>
      searchKeys.some((key) =>
        String(item[key] ?? '').toLowerCase().includes(lowercasedQuery)
      )
    );
  }, [data, searchQuery, searchKeys, onServerSearch]);

  return {
    searchQuery,
    handleSearch,
    filteredData,
  };
};