import { Spin } from 'antd';
import React, { Suspense } from 'react';
import SuspenseErrorBoundary from '../../common/components/SuspenseErrorBoundary';
import Utils from '../../common/helpers/Utils';

const MyImageComponent = (props) => {
  const {src} = useImage({
    srcList: [props.src],
  });

  return <img className="mx-auto sznThumbnail" src={src} alt="thumbnail"/>
}

const ImageLoader = <div className="mx-auto sznThumbnail" ><Spin className="text-muted" size="default"/></div>;

const ErrorImage = <img className="sznImg" src={Utils.backend + '/assets/common/img/image-not-found.png'} alt="404"/>;

const ProjectPreview = (props) => {
    return (
        <React.Fragment>
            <div className="cc-porfolio-image img-raised wow animate__animated animate__fadeInUp" data-aos="fade-up" data-aos-anchor-placement="top-bottom">
                <a href="/#" onClick={(e)=> {
                    e.preventDefault();
                }}>
                    <figure className="cc-effect">
                        <SuspenseErrorBoundary fallback={ErrorImage}>
                            <Suspense fallback={ImageLoader}>
                                <MyImageComponent src={'https://gw.alipayobjects.com/zos/rmsportal/JiqGstEfoWAOHiTxclqi.png'}/>
                            </Suspense>
                        </SuspenseErrorBoundary>
                        <figcaption>
                            <div>
                                <div className="h4">&nbsp;props.project.title &nbsp;</div>
                                <p>See Details</p>
                            </div>
                        </figcaption>
                    </figure>
                </a>
            </div>
        </React.Fragment>
    )
}

export default ProjectPreview;