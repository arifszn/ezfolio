import React from 'react';
import styled from 'styled-components';
import AvatarDropdown from './AvatarDropdown';

const Wrapper = styled.div`
    display: flex;
    float: right;
    margin-left: auto;
    overflow: hidden;
`;

const NavContent = () => {
    return (
        <React.Fragment>
            <Wrapper>
                <AvatarDropdown/>
            </Wrapper>
        </React.Fragment>
    )
}

export default NavContent;