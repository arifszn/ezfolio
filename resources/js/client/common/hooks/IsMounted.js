import { useRef, useEffect } from 'react';

export function useIsMounted() {
    const isMounted = useRef(false);

    useEffect(() => {
        isMounted.current = true;
    }, []);

    return isMounted.current;
}