import React, { useRef } from "react";
import PropTypes from 'prop-types';

import Sanitaze from '@commonFunctions/sanitaze';
import Styles from '@userFunctions/styles';

import { setHighlightClass, useHighlight } from "@commonHooks/item";

import { ButtonIcon } from "@userComponents/Elements/Button";
import { Badge } from "@userComponents/Elements/Badge";

export function OrdersItem ({ elem, highlight, onDelete })
{
    const refItem = useRef(null);

    let nHighlight = useHighlight(highlight, elem.id, refItem);

    let formation = elem.formation;

    return <div className={`item${setHighlightClass(nHighlight)} border-t hover:bg-slate-50`} ref={refItem}>
        <div className="item-content">
            <div className="item-infos">
                <div className="col-1">
                    <div className="infos">
                        <div className="text-gray-600 text-sm">
                            {Sanitaze.toDateFormat(elem.createdAt, 'L')}
                        </div>
                    </div>
                </div>
                <div className="col-2">
                    <span className="font-medium text-sm">{Sanitaze.toDateFormat(formation.startAt, 'L')}</span> - {formation.name}
                </div>
                <div className="col-3">
                    <span className="font-medium">{Sanitaze.toFormatCurrency(formation.priceHt)}</span>
                </div>
                <div className="col-4">
                    <Badge type={Styles.getBadgeType(elem.status)}>{elem.statusString}</Badge>
                </div>
                <div className="col-5 actions">
                    <ButtonIcon icon="trash" onClick={() => onDelete("delete", elem)}>Supprimer</ButtonIcon>
                </div>
            </div>
        </div>
    </div>
}

OrdersItem.propTypes = {
    elem: PropTypes.object.isRequired,
    onDelete: PropTypes.func.isRequired,
}
