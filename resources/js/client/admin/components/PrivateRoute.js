import { useSelector } from "react-redux";
import { Redirect, Route } from "react-router-dom";
import Routes from "../../common/helpers/Routes";

const PrivateRoute = ({ children, ...rest }) => {
    const token = useSelector(state => state.globalState.token);

    return (
        <Route
        {...rest}
        render={({ location }) =>
            token !== null ? (
            children
            ) : (
                <Redirect
                    to={{
                        pathname: Routes.web.admin.login,
                        state: { from: location }
                    }}
                />
            )
        }
        />
    );
}

export default PrivateRoute;