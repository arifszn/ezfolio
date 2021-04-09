import { Card, Col, Row } from 'antd';
import React, { useEffect, useState } from 'react';
import HTTP from '../../../common/helpers/HTTP';
import Routes from '../../../common/helpers/Routes';
import Utils from '../../../common/helpers/Utils';
import PageWrapper from '../layout/PageWrapper';
import FileUploader from '../uploader/FileUploader';

const PortfolioAbout = () => {
    const [avatar, setAvatar] = useState(null);
    const [name, setName] = useState('');
    const [email, setEmail] = useState('');
    const [phone, setPhone] = useState('');
    const [address, setAddress] = useState('');
    const [description, setDescription] = useState('');
    const [cv, setCv] = useState(null);
    const [taglines, setTagLines] = useState([]);
    const [socialLinks, setSocialLinks] = useState([]);
    
    const [focusTaglines, setFocusTaglines] = useState(false);
    const [focusSocialLinks, setFocusSocialLinks] = useState(false);

    const [componentLoading, setComponentLoading] = useState(true);
    const [loading, setLoading]= useState(false);
    const [socialLinkPopupVisible, setSocialLinkPopupVisible] = useState(false);
    const [socialLinkToEdit, setSocialLinkToEdit] = useState(null);

    useEffect(() => {
        loadData();
    }, [])

    const avatarUploadCallback = (file) => {
        setAvatar(file);
    }

    const loadData = () => {
        HTTP.get(Routes.api.admin.about)
        .then(response => {
            Utils.handleSuccessResponse(response, () => {
                setAvatar(response.data.payload.avatar);
                setName(response.data.payload.name);
                setEmail(response.data.payload.email);
                setPhone(response.data.payload.phone);
                setAddress(response.data.payload.address);
                setDescription(response.data.payload.description);
                setCv(response.data.payload.cv);
                setTagLines(response.data.payload.taglines ? JSON.parse(response.data.payload.taglines) : []);
                setSocialLinks(response.data.payload.social_links ? JSON.parse(response.data.payload.social_links) : []);
            })
        })
        .catch(error => {
            Utils.handleException(error);
        }).finally(() => {
            setComponentLoading(false);
        });
    }

    return (
        <React.Fragment>
            <Row gutter={24}>
                    <Col 
                        xl={8}
                        lg={8}
                        md={24}
                        sm={24}
                        xs={24}
                        style={{
                            marginBottom: 24,
                        }}
                    >
                        <Card bordered={false} hoverable className={'z-shadow'} loading={componentLoading}>
                           <FileUploader
                                allowRevert={true}
                                isAvatar={true}
                                previewAvatar={avatar && Utils.backend + '/' + avatar}
                                acceptedFileTypes={"image/*"}
                                allowMultiple={false}
                                name={'file'}
                                serverUrl={Routes.api.admin.avatar}
                                afterUploadCallback={avatarUploadCallback}
                                afterRevertCallback={avatarUploadCallback}
                           />
                        </Card>
                    </Col>
                    <Col 
                        xl={16}
                        lg={16}
                        md={24}
                        sm={24}
                        xs={24}
                        style={{
                            marginBottom: 24,
                        }}
                    >
                        <Card bordered={false} hoverable className={'z-shadow'} loading={componentLoading}>
                           gdfgdf
                        </Card>
                    </Col>
                </Row>
        </React.Fragment>
    )
}

export default PortfolioAbout;