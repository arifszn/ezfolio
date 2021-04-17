import React, { useEffect } from 'react';
import { useDispatch } from 'react-redux';
import { Redirect } from 'react-router-dom';
import Routes from '../../../common/helpers/Routes';
import { removeApiToken } from '../../redux/ActionCreators';

const Logout = () => {
    const dispatch = useDispatch();
    
    useEffect(() => {
        dispatch(removeApiToken());
    }, [])

    return (
        <Redirect
            to={Routes.web.admin.login}
        />
    );
}

export default Logout;