import React, { useRef } from "react";
import PropTypes from 'prop-types';
import Routing from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import Sanitaze from "@commonFunctions/sanitaze";

import { setHighlightClass, useHighlight } from "@commonHooks/item";

import { ButtonIcon } from "@commonComponents/Elements/Button";

const URL_UPDATE_PAGE = "manager_news_update";

export function NewsItem ({ elem, highlight, onDelete })
{
    const refItem = useRef(null);

    let nHighlight = useHighlight(highlight, elem.id, refItem);

    let urlUpdate = Routing.generate(URL_UPDATE_PAGE, {'id': elem.id});

    return <div className={`item${setHighlightClass(nHighlight)}`} ref={refItem}>
        <div className="item-content">
            <div className="item-infos">
                <div className="col-1 col-with-image">
                    <div className="image">
                        {elem.fileFile
                            ? <img src={elem.fileFile} alt="avatar" />
                            : null
                        }
                    </div>
                    <div className="infos">
                        <div className="name">
                            <span>{elem.name}</span>
                        </div>
                        <div className="sub">
                            {elem.updatedAt
                                ? "Mod. " + Sanitaze.toFormatCalendar(elem.updatedAt)
                                : Sanitaze.toFormatCalendar(elem.createdAt)
                            }
                        </div>
                    </div>
                </div>
                <div className="col-2">
                    <div class={`badge badge-news-${elem.visibility}`}>{elem.visibilityString}</div>
                </div>
                <div className="col-3 actions">
                    <ButtonIcon outline={true} icon="pencil" onClick={urlUpdate} element="a">Modifier</ButtonIcon>
                    <ButtonIcon outline={true} icon="trash" onClick={() => onDelete("delete", elem)}>Supprimer</ButtonIcon>
                </div>
            </div>
        </div>
    </div>
}

NewsItem.propTypes = {
    elem: PropTypes.object.isRequired,
    onDelete: PropTypes.func.isRequired,
}
