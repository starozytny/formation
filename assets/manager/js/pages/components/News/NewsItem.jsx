import React, { useRef } from "react";
import PropTypes from 'prop-types';
import Routing from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import Sanitaze from "@commonFunctions/sanitaze";
import Styles from "@userFunctions/styles";

import { setHighlightClass, useHighlight } from "@commonHooks/item";

import { ButtonIcon, ButtonIconA } from "@userComponents/Elements/Button";
import { Badge } from "@userComponents/Elements/Badge";

const URL_UPDATE_PAGE = "manager_news_update";

export function NewsItem ({ elem, highlight, onDelete })
{
    const refItem = useRef(null);

    let nHighlight = useHighlight(highlight, elem.id, refItem);

    let urlUpdate = Routing.generate(URL_UPDATE_PAGE, {'id': elem.id});

    return <div className={`item${setHighlightClass(nHighlight)} border-t hover:bg-slate-50`} ref={refItem}>
        <div className="item-content">
            <div className="item-infos">
                <div className="col-1 flex flex-row gap-4">
                    <div className="w-32 h-24 rounded-md overflow-hidden">
                        {elem.fileFile
                            ? <img src={elem.fileFile} alt="avatar" className="w-full h-full object-cover" />
                            : null
                        }
                    </div>
                    <div className="infos">
                        <div className="font-semibold">
                            <span>{elem.name}</span>
                        </div>
                        <div className="text-gray-600">
                            {elem.updatedAt
                                ? "Mod. " + Sanitaze.toFormatCalendar(elem.updatedAt)
                                : Sanitaze.toFormatCalendar(elem.createdAt)
                            }
                        </div>
                    </div>
                </div>
                <div className="col-2">
                    <Badge type={Styles.getBadgeType(elem.visibility)}>{elem.visibilityString}</Badge>
                </div>
                <div className="col-3 actions">
                    <ButtonIconA icon="pencil" link={urlUpdate}>Modifier</ButtonIconA>
                    <ButtonIcon type="default" icon="trash" onClick={() => onDelete("delete", elem)}>Supprimer</ButtonIcon>
                </div>
            </div>
        </div>
    </div>
}

NewsItem.propTypes = {
    elem: PropTypes.object.isRequired,
    onDelete: PropTypes.func.isRequired,
}
