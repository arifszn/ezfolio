import { Avatar } from 'antd';
import React from 'react';
import styled from 'styled-components';
import Routes from '../../../common/helpers/Routes';
import AvatarDropdown from './AvatarDropdown';
import { SelectOutlined } from '@ant-design/icons';

const Wrapper = styled.div`
    display: flex;
    float: right;
    margin-left: auto;
    overflow: hidden;
`;

const ItemWrapper = styled.div`
align-items: center;
height: 100%;
padding: 0 10px;
cursor: pointer;
transition: all .3s;
justify-content: center;
-webkit-box-align: center;
display: inline-flex;
&:hover {
    background: rgba(0,0,0,.025);
}
`;

const NavContent = () => {
    return (
        <React.Fragment>
            <Wrapper>
                <ItemWrapper className="z-hover">
                    <a href={Routes.web.frontend.home} target="_blank" rel="noreferrer" title="Visit Frontend">
                        <Avatar icon={<SelectOutlined />} size="small"/>
                    </a>
                </ItemWrapper>
                <ItemWrapper className="z-hover">
                    <AvatarDropdown/>
                </ItemWrapper>
            </Wrapper>
        </React.Fragment>
    )
}

export default NavContent;