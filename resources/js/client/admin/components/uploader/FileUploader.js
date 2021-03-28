import React, { useEffect, useState } from 'react';
import { FilePond, registerPlugin } from 'react-filepond';
import 'filepond/dist/filepond.min.css';
import FilePondPluginImageExifOrientation from 'filepond-plugin-image-exif-orientation';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginFilePoster from 'filepond-plugin-file-poster';
import 'filepond-plugin-file-poster/dist/filepond-plugin-file-poster.css';
import PropTypes from 'prop-types';
import Utils from '../../../common/helpers/Utils';

/**
 * Register Filepond plugins
 */
registerPlugin(
    FilePondPluginImageExifOrientation,
    FilePondPluginImagePreview, 
    FilePondPluginFileValidateSize, 
    FilePondPluginFileValidateType, 
    FilePondPluginFilePoster
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
 * bearerToken: string bearer token
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
                // pass poster property
                metadata: {
                    poster: props.previewFile
                }
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
                    // pass poster property
                    metadata: {
                        poster: props.previewFile
                    }
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
                filePosterMaxHeight={imagePreviewMaxHeight}
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
                        process: {
                            url: props.serverUrl,
                            headers: {
                                Authorization: `Bearer ${props.bearerToken}`
                            },
                            load: (source, load) => {
                                var myRequest = new Request(source);
                                fetch(myRequest)
                                .then(response => {
                                    response.blob()
                                    .then(myBlob => {
                                        load(myBlob);
                                    })
                                    .catch(myErr => {
                                        console.log(myErr);
                                    });
                                }).catch(err => {
                                    console.log(err);
                                });
                            },
                            onload: (response) => {
                                response = JSON.parse(response);
                                Utils.handleSuccessResponse(response, () => {
                                    if (props.afterUploadCallback) {
                                        props.afterUploadCallback(response);
                                    }
                                    return response.payload.file;
                                });
                            },
                            onerror: (response) => {
                                console.log(response);
                                Utils.showNotification('Something went wrong in file upload', 'error', null);
                            },
                            ondata: (formData) => {
                                //Called with the formdata object right before it is sent, return extended formdata object to make changes
                                // formData.append('Hello', 'World');
                                return formData;
                            }
                        },
                        revert: {
                            url: props.serverUrl,
                            headers: {
                                Authorization: `Bearer ${props.bearerToken}`
                            },
                            onload: (response) => {
                                response = JSON.parse(response);
                                Utils.handleSuccessResponse(response, () => {
                                    if (props.afterRevertCallback) {
                                        props.afterRevertCallback(response);
                                    }
                                });
                            },
                            onerror: (response) => {
                                console.log(response);
                                Utils.showNotification('Something went wrong in file revert', 'error', null);
                            }
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
    bearerToken: PropTypes.string,
    serverUrl: PropTypes.string,
    previewFile: PropTypes.string,
    name: PropTypes.string,
    maxFiles: PropTypes.number,
    maxFileSize: PropTypes.number,
    allowMultiple: PropTypes.bool,
    allowRevert: PropTypes.bool,
    limbo: PropTypes.bool,
    acceptedFileTypes: PropTypes.array,
    imagePreviewMaxHeight: PropTypes.number,
}

export default FileUploader;