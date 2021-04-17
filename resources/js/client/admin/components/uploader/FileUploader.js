import React, { useEffect, useState } from 'react';
import { FilePond, registerPlugin } from 'react-filepond';
import 'filepond/dist/filepond.min.css';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import PropTypes from 'prop-types';
import Utils from '../../../common/helpers/Utils';
import HTTP from '../../../common/helpers/HTTP';
import axios from 'axios';
import styled from 'styled-components';

/**
 * Register Filepond plugins
 */
registerPlugin(
    FilePondPluginImagePreview,
    FilePondPluginFileValidateSize, 
    FilePondPluginFileValidateType, 
);

const avatarProps = {
    imageCropAspectRatio: '1:1',
    imageResizeTargetWidth: 200,
    imageResizeTargetHeight: 200,
    styleLoadIndicatorPosition: 'right bottom',
    styleButtonRemoveItemPosition: 'left bottom',
    styleButtonProcessItemPosition: 'center bottom',
    styleProgressIndicatorPosition: 'center bottom',
}

const AvatarWrapper = styled.div`
.filepond--drop-label {
    ${props => props.preview && `background-image: url(${props.preview});`}
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
    color: #4c4e53;
}

.filepond--drop-label label {
    cursor: pointer;
    width: 100%;
    align-self: flex-end!important;
    background: #333333b8;
    color: #eee;
}

.filepond--drop-label.filepond--drop-label label {
    font-size: 12px;
}

.filepond--label-action {
    text-decoration-color: #babdc0;
    text-decoration: none;
}

.filepond--panel-root {
    background-color: #edf0f4;
}

.filepond--root {
    width:170px;
    margin: 0 auto;
}

.filepond--label-action:focus {
    outline: none;
}
`;

/**
 * File uploader global component(Filepond instance)
 * 
 * Props definition-
 * isAvatar: bool|null Uploader is avatar or not
 * previewFile: string|null Full path of file to preview as default
 * previewAvatar: string|null Full path of avatar to preview as default
 * limbo: boolean Already uploaded or not
 * imagePreviewHeight: int|null
 * allowRevert: boolean|null Allow revert option after successful upload
 * acceptedFileTypes: boolean|null
 * maxFileSize: string|null example: 5MB
 * allowMultiple: boolean|null
 * maxFiles: int|null
 * labelIdle: string|null
 * name: string|null name of the filepond input which will be sent to server
 * serverUrl: string server url
 * afterUploadCallback: function Callback function after successful upload
 * afterRevertCallback: function|null Callback function after successful revert
 * 
 * @param {*} props 
 */
const FileUploader = (props) => {
    const limbo = typeof props.limbo !=='undefined' ? props.limbo : false;
    const [files, setFiles] = useState(props.previewFile ?
        [{
            // the server file reference
            source: props.previewFile,

            // set type to local to indicate preview file
            // set type to limbo to indicate an already server uploaded file
            options: {
                type: limbo ? 'limbo' : 'local',
            }
        }] :
        []
    );

    useEffect(() => {
        if (props.previewFile) {
            setFiles([{
                // the server file reference
                source: props.previewFile,
                // set type to local to indicate an already uploaded file
                options: {
                    type: limbo ? 'limbo' : 'local',
                }
            }]);
        }
    }, [props.previewFile])

    const imagePreviewMaxHeight = props.imagePreviewMaxHeight ? props.imagePreviewMaxHeight : 150;

    const renderFilePond = () => {
        return (
            <FilePond
                stylePanelLayout={props.isAvatar ? 'compact circle' :'compact'}
                instantUpload={false}
                allowRevert={typeof props.allowRevert !== 'undefined' ? props.allowRevert : false}
                allowFileSizeValidation={true}
                allowFileTypeValidation={props.acceptedFileTypes ? true : false}
                imagePreviewMaxHeight={imagePreviewMaxHeight}
                acceptedFileTypes={props.acceptedFileTypes ? props.acceptedFileTypes : []}
                maxTotalFileSize={props.maxFileSize ? props.maxFileSize : '5MB'}
                labelMaxFileSizeExceeded={'File is too large'}
                labelMaxFileSize={'Maximum file size is {filesize}'}
                files={files}
                onupdatefiles={setFiles}
                allowMultiple={props.allowMultiple ? props.allowMultiple : false}
                maxFiles={props.maxFiles ? props.maxFiles : 3}
                name={props.name ? props.name : 'file'}
                credits={false}
                server={
                    {   
                        load: (source, load, error, progress, abort) => {
                            let myRequest = new Request(source);
                                fetch(myRequest)
                                .then(response => {
                                    response.blob()
                                    .then(myBlob => {
                                        load(myBlob);
                                    })
                                    .catch(myErr => {
                                        console.log(myErr);
                                        error('Something went wrong');
                                    });
                                }).catch(err => {
                                    console.log(err);
                                    error('Something went wrong');
                                });

                            // Should expose an abort method so the request can be cancelled
                            return {
                                abort: () => {
                                    // User tapped cancel, abort our ongoing actions here

                                    // Let FilePond know the request has been cancelled
                                    abort();
                                }
                            };
                        },
                        process:(fieldName, file, metadata, load, error, progress, abort) => {

                            // fieldName is the name of the input field
                            // file is the actual file object to send
                            const formData = new FormData();
                            formData.append(fieldName, file, file.name);

                            // related to aborting the request
                            const CancelToken = axios.CancelToken;

                            const source = CancelToken.source();

                            const config = {
                                onUploadProgress: e => progress(e.lengthComputable, e.loaded, e.total)
                            }

                            HTTP.post(props.serverUrl, formData, config)
                            .then((response) => {
                                Utils.handleSuccessResponse(response, () => {
                                    if (props.afterUploadCallback) {
                                        props.afterUploadCallback(response.data.payload.file);
                                    }
                                    load(response.data.payload.file);
                                });
                            }).catch((e) => {
                                error('Something went wrong');
                                Utils.handleException(e);
                            });

                            // Should expose an abort method so the request can be cancelled
                            return {
                                abort: () => {
                                    // This function is entered if the user has tapped the cancel button
                                    source.cancel('Operation canceled by the user.');

                                    // Let FilePond know the request has been cancelled
                                    abort();
                                }
                            };
                        },
                        revert: (file, load, error) => {
                            HTTP.delete(props.serverUrl, {
                                params: {
                                    file: file
                                }
                            })
                            .then((response) => {
                                Utils.handleSuccessResponse(response, () => {
                                    if (props.afterRevertCallback) {
                                        props.afterRevertCallback(response.data.payload.file);
                                    }
                                    load();
                                });
                            }).catch((e) => {
                                error('Something went wrong');
                                Utils.handleException(e);
                            });

                        }
                    }
                }
                labelIdle={props.labelIdle ? props.labelIdle : (props.isAvatar ? '<span class="filepond--label-action">Change</span>' :'Drag & Drop your file or <span class="filepond--label-action">Browse</span>')}
                {...(props.isAvatar && avatarProps)}
            />
        )
    }

    return (
        <React.Fragment>
            {
                props.isAvatar ? (
                    <AvatarWrapper preview={typeof props.previewAvatar !== 'undefined' && props.previewAvatar}>
                        {renderFilePond()}
                    </AvatarWrapper>
                ) : (
                    renderFilePond()
                )
            }
        </React.Fragment>
    );
};

FileUploader.propTypes = {
    labelIdle: PropTypes.string,
    afterUploadCallback: PropTypes.func,
    afterRevertCallback: PropTypes.func,
    serverUrl: PropTypes.string.isRequired,
    previewFile: PropTypes.string,
    previewAvatar: PropTypes.string,
    name: PropTypes.string,
    maxFiles: PropTypes.number,
    maxFileSize: PropTypes.number,
    allowMultiple: PropTypes.bool,
    isAvatar: PropTypes.bool,
    allowRevert: PropTypes.bool,
    limbo: PropTypes.bool,
    acceptedFileTypes: PropTypes.oneOfType([
        PropTypes.string,
        PropTypes.array
    ]),
    imagePreviewMaxHeight: PropTypes.number,
}

export default FileUploader;