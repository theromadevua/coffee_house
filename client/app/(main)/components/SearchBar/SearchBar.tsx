'use client'

import { FaSearch } from "react-icons/fa";
import styles from './searchBar.module.css';
import Categories from "../Categories/Categories";
import { useEffect, useState, useRef } from "react";
import { useDishStore } from "@/store/dishStore";

const SearchBar = () => {
    const [query, setQuery] = useState<string>(''); 
    const { searchDishes } = useDishStore();
    const isInitialMount = useRef(true); 

    useEffect(() => {
        if (isInitialMount.current) {
            isInitialMount.current = false;
            return;
        }

        const debounceTimer = setTimeout(() => {
            searchDishes(query); 
        }, 500);

        return () => clearTimeout(debounceTimer);
    }, [query, searchDishes]);

    return (
        <div className={styles.searchBar}>
            <Categories />
            <div className={styles.container}>
                <FaSearch className={styles.navIcon} />
                <input 
                    className={styles.searchInput} 
                    placeholder="Search items"
                    value={query}
                    onChange={(e) => setQuery(e.target.value)}
                />
            </div>
        </div>
    );
};

export default SearchBar;