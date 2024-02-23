import React, { useRef } from "react";
import PropTypes from 'prop-types';
import Routing from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import { setHighlightClass, useHighlight } from "@commonHooks/item";

import { ButtonIcon } from "@commonComponents/Elements/Button";

const URL_UPDATE_PAGE = "manager_settings_taxs_update";

export function TaxsItem ({ elem, highlight, onDelete })
{
    const refItem = useRef(null);

    let nHighlight = useHighlight(highlight, elem.id, refItem);

    let urlUpdate = Routing.generate(URL_UPDATE_PAGE, {'id': elem.id});

    return <div className={`item${setHighlightClass(nHighlight)}`} ref={refItem}>
        <div className="item-content">
            <div className="item-infos">
                <div className="col-1">
                    <div className="infos">
                        <div className="name">
                            <span>{elem.code}</span>
                        </div>
                    </div>
                </div>
                <div className="col-2">
                    <div>{elem.taux} %</div>
                </div>
                <div className="col-3">
                    <div className="sub">{elem.numeroCompta}</div>
                </div>
                <div className="col-4 actions">
                    <ButtonIcon outline={true} icon="pencil" onClick={urlUpdate} element="a">Modifier</ButtonIcon>
                    <ButtonIcon outline={true} icon="trash" onClick={() => onDelete("delete", elem)}>Supprimer</ButtonIcon>
                </div>
            </div>
        </div>
    </div>
}

TaxsItem.propTypes = {
    elem: PropTypes.object.isRequired,
    onDelete: PropTypes.func.isRequired,
}
