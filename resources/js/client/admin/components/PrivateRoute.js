import React from 'react';
import { useSelector } from "react-redux";
import { Redirect, Route } from "react-router-dom";
import Routes from "../../common/helpers/Routes";
import PropTypes from 'prop-types';

const PrivateRoute = ({ children, ...rest }) => {
    const apiToken = useSelector(state => state.globalState.apiToken);

    return (
        <Route
            {...rest}
            render={({ location }) =>
                (typeof apiToken !=='undefined' && apiToken !== null) ? (
                    children
                ) : (
                    <Redirect
                        to={{
                            pathname: Routes.web.admin.login,
                            intended: { from: location }
                        }}
                    />
                )
            }
        />
    );
}

PrivateRoute.propTypes = {
    children: PropTypes.node,
}

export default PrivateRoute;