import React, { useEffect, useState } from 'react';
import { FilePond, registerPlugin } from 'react-filepond';
import 'filepond/dist/filepond.min.css';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import PropTypes from 'prop-types';

/**
 * Register Filepond plugins
 */
registerPlugin(
    FilePondPluginImagePreview, 
    FilePondPluginFileValidateSize, 
    FilePondPluginFileValidateType, 
);

/**
 * File uploader form input global component(Filepond instance)
 * 
 * Props definition-
 * previewFile: string|null Full path of file to preview as default
 * limbo: boolean Already uploaded or not
 * imagePreviewHeight: int|null
 * acceptedFileTypes: boolean|null
 * maxFileSize: string|null example: 5MB
 * allowMultiple: boolean|null
 * maxFiles: int|null
 * disabled: boolean|null
 * labelIdle: string|null
 * name: string|null name of the filepond input which will be sent to server
 * onChangeCallback: func 
 * 
 * @param {*} props 
 */
const FileUploaderFormInput = (props) => {
    const limbo = typeof props.limbo !=='undefined' ? props.limbo : false;
    const [files, setFiles] = useState(props.previewFile ?
        [{
            // the server file reference
            source: props.previewFile,
            // set type to local to indicate an already uploaded file
            options: {
                type: 'local',
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

    const imagePreviewMaxHeight = 150;

    return (
        <React.Fragment>
             <FilePond
                disabled={props.disabled ? true : false}
                stylePanelLayout={'compact'}
                instantUpload={false}
                allowFileSizeValidation={true}
                allowFileTypeValidation={props.acceptedFileTypes ? true : false}
                imagePreviewMaxHeight={imagePreviewMaxHeight}
                acceptedFileTypes={props.acceptedFileTypes ? props.acceptedFileTypes : []}
                maxTotalFileSize={props.maxFileSize ? props.maxFileSize : '5MB'}
                labelMaxFileSizeExceeded={'File is too large'}
                labelMaxFileSize={'Maximum file size is {filesize}'}
                allowProcess={false}
                allowRevert={false}
                files={files}
                credits={false}
                server={
                    {
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
                        }
                    }
                }
                onupdatefiles={(fileItems) => {
                    let files = fileItems.map(fileItem => fileItem.file);
                    setFiles(files);
                    if (props.onChangeCallback) {
                        props.onChangeCallback(files);
                    }
                }}
                allowMultiple={props.allowMultiple ? props.allowMultiple : false}
                maxFiles={props.maxFiles ? props.maxFiles : 3}
                name={props.name ? props.name : 'file'}
                labelIdle={props.labelIdle ? props.labelIdle : 'Drag & Drop your file or <span class="filepond--label-action">Browse</span>'}
            />
        </React.Fragment>
    );
};

FileUploaderFormInput.propTypes = {
    labelIdle: PropTypes.string,
    disabled: PropTypes.bool,
    previewFile: PropTypes.string,
    name: PropTypes.string,
    maxFiles: PropTypes.number,
    onChangeCallback: PropTypes.func,
    maxFileSize: PropTypes.number,
    allowMultiple: PropTypes.bool,
    limbo: PropTypes.bool,
    acceptedFileTypes: PropTypes.oneOfType([
        PropTypes.string,
        PropTypes.array
    ]),
    imagePreviewMaxHeight: PropTypes.number,
}

export default FileUploaderFormInput;