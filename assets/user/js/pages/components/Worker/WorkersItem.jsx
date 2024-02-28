import React, { useRef } from "react";
import PropTypes from 'prop-types';
import Routing from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import { setHighlightClass, useHighlight } from "@commonHooks/item";

import { ButtonIconA, ButtonIcon } from "@userComponents/Elements/Button";

const URL_UPDATE_PAGE = "user_workers_update";

export function WorkersItem ({ elem, highlight, onDelete })
{
    const refItem = useRef(null);

    let nHighlight = useHighlight(highlight, elem.id, refItem);

    let urlUpdate = Routing.generate(URL_UPDATE_PAGE, {'id': elem.id});

    return <div className={`item${setHighlightClass(nHighlight)} border-t hover:bg-slate-50`} ref={refItem}>
        <div className="item-content">
            <div className="item-infos">
                <div className="col-1">
                    <div className="infos">
                        <div className="font-semibold">
                            <div>
                                {elem.lastname} {elem.firstname}
                            </div>
                        </div>
                    </div>
                </div>
                <div className="col-2">
                    <div className="text-gray-600">{elem.email}</div>
                </div>
                <div className="col-3 actions">
                    <ButtonIconA icon="pencil" link={urlUpdate}>Modifier</ButtonIconA>
                    <ButtonIcon type="default" icon="trash" onClick={() => onDelete("delete", elem)}>Supprimer</ButtonIcon>
                </div>
            </div>
        </div>
    </div>
}

WorkersItem.propTypes = {
    elem: PropTypes.object.isRequired,
    onDelete: PropTypes.func.isRequired,
}
