import React from 'react';
import PropTypes from 'prop-types';

/**
 * SuspenseErrorBoundary component to catch errors
 */
class SuspenseErrorBoundary extends React.Component {
    constructor(props) {
        super(props);
        this.state = { hasError: false };
    }

    // eslint-disable-next-line no-unused-vars
    static getDerivedStateFromError(error) {
        // Update state so the next render will show the fallback UI.
        return { hasError: true };
    }

    // eslint-disable-next-line no-unused-vars
    componentDidCatch(error, errorInfo) {
        // You can also log the error to an error reporting service
        // logErrorToMyService(error, errorInfo);
    }

    render() {
        if (this.state.hasError) {
            // You can render any custom fallback UI
            return this.props.fallback ? this.props.fallback : <h1>Something went wrong.</h1>;
        }

        return this.props.children;
    }
}

SuspenseErrorBoundary.propTypes = {
    fallback: PropTypes.node,
    children: PropTypes.node
}

export default SuspenseErrorBoundary;