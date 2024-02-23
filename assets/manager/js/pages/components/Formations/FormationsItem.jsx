import React, { useRef } from "react";
import PropTypes from 'prop-types';
import Routing from '@publicFolder/bundles/fosjsrouting/js/router.min.js';

import Sanitaze from "@commonFunctions/sanitaze";

import { setHighlightClass, useHighlight } from "@commonHooks/item";

import { ButtonIcon } from "@commonComponents/Elements/Button";

const URL_UPDATE_PAGE = "manager_formations_update";

export function FormationsItem ({ elem, highlight, onDelete })
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
                            <div>
                                {Sanitaze.toDateFormat(elem.startAt, 'L')}
                            </div>
                            <div>
                                {elem.startAt !== elem.endAt ? " au " + Sanitaze.toDateFormat(elem.endAt, 'L') : null}
                            </div>
                        </div>
                    </div>
                </div>
                <div className="col-2">
                    <div>{elem.name}</div>
                </div>
                <div className="col-3">
                    <div>{Sanitaze.toFormatCurrency(elem.priceHt)} HT</div>
                    <div className="sub" style={{marginTop: "4px"}}>
                        <div>{elem.nbRemain} / {elem.nbMax} places</div>
                        <div>{elem.nbMin} places min</div>
                    </div>
                </div>
                <div className="col-4">
                    <div className="sub">{elem.isOnline ? "En ligne" : "Hors ligne"}</div>
                </div>
                <div className="col-5 actions">
                    <ButtonIcon outline={true} icon="pencil" onClick={urlUpdate} element="a">Modifier</ButtonIcon>
                    <ButtonIcon outline={true} icon="trash" onClick={() => onDelete("delete", elem)}>Supprimer</ButtonIcon>
                </div>
            </div>
        </div>
    </div>
}

FormationsItem.propTypes = {
    elem: PropTypes.object.isRequired,
    onDelete: PropTypes.func.isRequired,
}
