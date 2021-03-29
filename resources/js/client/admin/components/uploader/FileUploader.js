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

/**
 * Register Filepond plugins
 */
registerPlugin(
    FilePondPluginImagePreview,
    FilePondPluginFileValidateSize, 
    FilePondPluginFileValidateType, 
);

/**
 * File uploader global component(Filepond instance)
 * 
 * Props definition-
 * previewFile: string|null Full path of file to preview as default
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

    return (
        <React.Fragment>
             <FilePond
                stylePanelLayout={'compact'}
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
                name={props.name ? props.name : 'filePond'}
                credits={false}
                server={
                    {   
                        load: (source, load, error, progress, abort) => {
                            var myRequest = new Request(source);
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
                labelIdle={props.labelIdle ? props.labelIdle : 'Drag & Drop your file or <span class="filepond--label-action">Browse</span>'}
            />
        </React.Fragment>
    );
};

FileUploader.propTypes = {
    labelIdle: PropTypes.string,
    afterUploadCallback: PropTypes.func,
    afterRevertCallback: PropTypes.func,
    serverUrl: PropTypes.string,
    previewFile: PropTypes.string,
    name: PropTypes.string,
    maxFiles: PropTypes.number,
    maxFileSize: PropTypes.number,
    allowMultiple: PropTypes.bool,
    allowRevert: PropTypes.bool,
    limbo: PropTypes.bool,
    acceptedFileTypes: PropTypes.oneOfType([
        PropTypes.string,
        PropTypes.array
    ]),
    imagePreviewMaxHeight: PropTypes.number,
}

export default FileUploader;